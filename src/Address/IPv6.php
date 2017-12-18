<?php

namespace IPLib\Address;

use IPLib\Range\RangeInterface;
use IPLib\Range\Subnet;
use IPLib\Range\Type as RangeType;

/**
 * An IPv6 address.
 */
class IPv6 implements AddressInterface
{
    /**
     * The long string representation of the address.
     *
     * @var string
     *
     * @example '0000:0000:0000:0000:0000:0000:0000:0001'
     */
    protected $longAddress;

    /**
     * The long string representation of the address.
     *
     * @var string|null
     *
     * @example '::1'
     */
    protected $shortAddress;

    /**
     * The byte list of the IP address.
     *
     * @var int[]|null
     */
    protected $bytes;

    /**
     * The word list of the IP address.
     *
     * @var int[]|null
     */
    protected $words;

    /**
     * The type of the range of this IP address.
     *
     * @var int|null
     */
    protected $rangeType;

    /**
     * An array containing RFC designated address ranges.
     *
     * @var array|null
     */
    private static $reservedRanges = null;

    /**
     * Initializes the instance.
     *
     * @param string $longAddress
     */
    public function __construct($longAddress)
    {
        $this->longAddress = $longAddress;
        $this->shortAddress = null;
        $this->bytes = null;
        $this->words = null;
        $this->rangeType = null;
    }

    /**
     * Parse a string and returns an IPv6 instance if the string is valid, or null otherwise.
     *
     * @param string|mixed $address the address to parse
     * @param bool $mayIncludePort set to false to avoid parsing addresses with ports
     * @param bool $mayIncludeZoneID set to false to avoid parsing addresses with zone IDs (see RFC 4007)
     *
     * @return static|null
     */
    public static function fromString($address, $mayIncludePort = true, $mayIncludeZoneID = true)
    {
        $result = null;
        if (is_string($address) && strpos($address, ':') !== false && strpos($address, ':::') === false) {
            if ($mayIncludePort && $address[0] === '[' && preg_match('/^\[(.+)\]:\d+$/', $address, $matches)) {
                $address = $matches[1];
            }
            if ($mayIncludeZoneID) {
                $percentagePos = strpos($address, '%');
                if ($percentagePos > 0) {
                    $address = substr($address, 0, $percentagePos);
                }
            }
            if (preg_match('/^([0:]+:ffff:)(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})$/i', $address, $matches)) {
                // IPv4 embedded in IPv6
                $address6 = static::fromString($matches[1].'0:0', false);
                if ($address6 !== null) {
                    $address4 = IPv4::fromString($matches[2], false);
                    if ($address4 !== null) {
                        $bytes4 = $address4->getBytes();
                        $address6->longAddress = substr($address6->longAddress, 0, -9).sprintf('%02x%02x:%02x%02x', $bytes4[0], $bytes4[1], $bytes4[2], $bytes4[3]);
                        $result = $address6;
                    }
                }
            } else {
                if (strpos($address, '::') === false) {
                    $chunks = explode(':', $address);
                } else {
                    $chunks = array();
                    $parts = explode('::', $address);
                    if (count($parts) === 2) {
                        $before = ($parts[0] === '') ? array() : explode(':', $parts[0]);
                        $after = ($parts[1] === '') ? array() : explode(':', $parts[1]);
                        $missing = 8 - count($before) - count($after);
                        if ($missing >= 0) {
                            $chunks = $before;
                            if ($missing !== 0) {
                                $chunks = array_merge($chunks, array_fill(0, $missing, '0'));
                            }
                            $chunks = array_merge($chunks, $after);
                        }
                    }
                }
                if (count($chunks) === 8) {
                    $nums = array_map(
                        function ($chunk) {
                            return preg_match('/^[0-9A-Fa-f]{1,4}$/', $chunk) ? hexdec($chunk) : false;
                        },
                        $chunks
                    );
                    if (!in_array(false, $nums, true)) {
                        $longAddress = implode(
                            ':',
                            array_map(
                                function ($num) {
                                    return sprintf('%04x', $num);
                                },
                                $nums
                            )
                        );
                        $result = new static($longAddress);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Parse an array of bytes and returns an IPv6 instance if the array is valid, or null otherwise.
     *
     * @param int[]|array $bytes
     *
     * @return static|null
     */
    public static function fromBytes(array $bytes)
    {
        $result = null;
        if (count($bytes) === 16) {
            $address = '';
            for ($i = 0; $i < 16; ++$i) {
                if ($i !== 0 && $i % 2 === 0) {
                    $address .= ':';
                }
                $byte = $bytes[$i];
                if (is_int($byte) && $byte >= 0 && $byte <= 255) {
                    $address .= sprintf('%02x', $byte);
                } else {
                    $address = null;
                    break;
                }
            }
            if ($address !== null) {
                $result = new static($address);
            }
        }

        return $result;
    }

    /**
     * Parse an array of words and returns an IPv6 instance if the array is valid, or null otherwise.
     *
     * @param int[]|array $words
     *
     * @return static|null
     */
    public static function fromWords(array $words)
    {
        $result = null;
        if (count($words) === 8) {
            $chunks = array();
            for ($i = 0; $i < 8; ++$i) {
                $word = $words[$i];
                if (is_int($word) && $word >= 0 && $word <= 0xffff) {
                    $chunks[] = sprintf('%04x', $word);
                } else {
                    $chunks = null;
                    break;
                }
            }
            if ($chunks !== null) {
                $result = new static(implode(':', $chunks));
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::toString()
     */
    public function toString($long = false)
    {
        if ($long) {
            $result = $this->longAddress;
        } else {
            if ($this->shortAddress === null) {
                if (strpos($this->longAddress, '0000:0000:0000:0000:0000:ffff:') === 0) {
                    $lastBytes = array_slice($this->getBytes(), -4);
                    $this->shortAddress = '::ffff:'.implode('.', $lastBytes);
                } else {
                    $chunks = array_map(
                        function ($word) {
                            return dechex($word);
                        },
                        $this->getWords()
                    );
                    $shortAddress = implode(':', $chunks);
                    for ($i = 8; $i > 1; --$i) {
                        $search = '(?:^|:)'.rtrim(str_repeat('0:', $i), ':').'(?:$|:)';
                        if (preg_match('/^(.*?)'.$search.'(.*)$/', $shortAddress, $matches)) {
                            $shortAddress = $matches[1].'::'.$matches[2];
                            break;
                        }
                    }
                    $this->shortAddress = $shortAddress;
                }
            }
            $result = $this->shortAddress;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::__toString()
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getBytes()
     */
    public function getBytes()
    {
        if ($this->bytes === null) {
            $bytes = array();
            foreach ($this->getWords() as $word) {
                $bytes[] = $word >> 8;
                $bytes[] = $word & 0xff;
            }
            $this->bytes = $bytes;
        }

        return $this->bytes;
    }

    /**
     * Get the word list of the IP address.
     *
     * @return int[]
     */
    public function getWords()
    {
        if ($this->words === null) {
            $this->words = array_map(
                function ($chunk) {
                    return hexdec($chunk);
                },
                explode(':', $this->longAddress)
            );
        }

        return $this->words;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getAddressType()
     */
    public function getAddressType()
    {
        return Type::T_IPv6;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getDefaultReservedRangeType()
     */
    public static function getDefaultReservedRangeType()
    {
        return RangeType::T_RESERVED;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getReservedRanges()
     */
    public static function getReservedRanges()
    {
        if (self::$reservedRanges === null) {
            $reservedRanges = array();
            foreach (array(
                // RFC 4291
                '::/128' => array(RangeType::T_UNSPECIFIED),
                // RFC 4291
                '::1/128' => array(RangeType::T_LOOPBACK),
                // RFC 4291
                '100::/8' => array(RangeType::T_DISCARD, array('100::/64' => RangeType::T_DISCARDONLY)),
                //'2002::/16' => array(RangeType::),
                // RFC 4291
                '2000::/3' => array(RangeType::T_PUBLIC),
                // RFC 4193
                'fc00::/7' => array(RangeType::T_PRIVATENETWORK),
                // RFC 4291
                'fe80::/10' => array(RangeType::T_LINKLOCAL_UNICAST),
                // RFC 4291
                'ff00::/8' => array(RangeType::T_MULTICAST),
                // RFC 4291
                //'::/8' => array(RangeType::T_RESERVED),
                // RFC 4048
                //'200::/7' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'400::/6' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'800::/5' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'1000::/4' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'4000::/3' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'6000::/3' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'8000::/3' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'a000::/3' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'c000::/3' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'e000::/4' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'f000::/5' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'f800::/6' => array(RangeType::T_RESERVED),
                // RFC 4291
                //'fe00::/9' => array(RangeType::T_RESERVED),
                // RFC 3879
                //'fec0::/10' => array(RangeType::T_RESERVED),
            ) as $range => $data) {
                $exceptions = array();
                if (isset($data[1])) {
                    foreach ($data[1] as $exceptionRange => $exceptionType) {
                        $exceptions[] = new AssignedRange(Subnet::fromString($exceptionRange), $exceptionType);
                    }
                }
                $reservedRanges[] = new AssignedRange(Subnet::fromString($range), $data[0], $exceptions);
            }
            self::$reservedRanges = $reservedRanges;
        }

        return self::$reservedRanges;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getRangeType()
     */
    public function getRangeType()
    {
        if ($this->rangeType === null) {
            $ipv4 = $this->toIPv4();
            if ($ipv4 !== null) {
                $this->rangeType = $ipv4->getRangeType();
            } else {
                $rangeType = null;
                foreach (static::getReservedRanges() as $reservedRange) {
                    $rangeType = $reservedRange->getAddressType($this);
                    if ($rangeType !== null) {
                        break;
                    }
                }
                $this->rangeType = $rangeType === null ? static::getDefaultReservedRangeType() : $rangeType;
            }
        }

        return $this->rangeType;
    }

    /**
     * Create an IPv4 representation of this address (if possible, otherwise returns null).
     *
     * @return IPv4|null
     */
    public function toIPv4()
    {
        $result = null;
        if (strpos($this->longAddress, '2002:') === 0) {
            $result = IPv4::fromBytes(array_slice($this->getBytes(), 2, 4));
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getComparableString()
     */
    public function getComparableString()
    {
        return $this->longAddress;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::matches()
     */
    public function matches(RangeInterface $range)
    {
        return $range->contains($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getNextAddress()
     */
    public function getNextAddress()
    {
        $overflow = false;
        $words = $this->getWords();
        for ($i = count($words) - 1; $i >= 0; --$i) {
            if ($words[$i] === 0xffff) {
                if ($i === 0) {
                    $overflow = true;
                    break;
                }
                $words[$i] = 0;
            } else {
                ++$words[$i];
                break;
            }
        }

        return $overflow ? null : static::fromWords($words);
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getPreviousAddress()
     */
    public function getPreviousAddress()
    {
        $overflow = false;
        $words = $this->getWords();
        for ($i = count($words) - 1; $i >= 0; --$i) {
            if ($words[$i] === 0) {
                if ($i === 0) {
                    $overflow = true;
                    break;
                }
                $words[$i] = 0xffff;
            } else {
                --$words[$i];
                break;
            }
        }

        return $overflow ? null : static::fromWords($words);
    }
}
