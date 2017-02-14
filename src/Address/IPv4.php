<?php

namespace IPLib\Address;

use IPLib\Range\RangeInterface;
use IPLib\Range\Subnet;
use IPLib\Range\Type as RangeType;

/**
 * An IPv4 address.
 */
class IPv4 implements AddressInterface
{
    /**
     * The string representation of the address.
     *
     * @var string
     *
     * @example '127.0.0.1'
     */
    protected $address;

    /**
     * The byte list of the IP address.
     *
     * @var int[]|null
     */
    protected $bytes;

    /**
     * The type of the range of this IP address.
     *
     * @var int|null
     */
    protected $rangeType;

    /**
     * A string representation of this address than can be used when comparing addresses and ranges.
     *
     * @var string
     */
    protected $comparableString;

    /**
     * Initializes the instance.
     *
     * @param string $address
     */
    protected function __construct($address)
    {
        $this->address = $address;
        $this->bytes = null;
        $this->rangeType = null;
        $this->comparableString = null;
    }

    /**
     * Parse a string and returns an IPv4 instance if the string is valid, or null otherwise.
     *
     * @param string|mixed $address the address to parse
     * @param bool $mayIncludePort set to false to avoid parsing addresses with ports
     *
     * @return static|null
     */
    public static function fromString($address, $mayIncludePort = true)
    {
        $result = null;
        if (is_string($address) && strpos($address, '.')) {
            $rx = '([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})';
            if ($mayIncludePort) {
                $rx .= '(?::\d+)?';
            }
            if (preg_match('/^'.$rx.'$/', $address, $matches)) {
                $ok = true;
                $nums = array();
                for ($i = 1; $ok && $i <= 4; ++$i) {
                    $ok = false;
                    $n = (int) $matches[$i];
                    if ($n >= 0 && $n <= 255) {
                        $ok = true;
                        $nums[] = (string) $n;
                    }
                }
                if ($ok) {
                    $result = new static(implode('.', $nums));
                }
            }
        }

        return $result;
    }

    /**
     * Parse an array of bytes and returns an IPv4 instance if the array is valid, or null otherwise.
     *
     * @param int[]|array $bytes
     *
     * @return static|null
     */
    public static function fromBytes(array $bytes)
    {
        $result = null;
        if (count($bytes) === 4) {
            $chunks = array_map(
                function ($byte) {
                    return (is_int($byte) && $byte >= 0 && $byte <= 255) ? (string) $byte : false;
                },
                $bytes
            );
            if (in_array(false, $chunks, true) === false) {
                $result = new static(implode('.', $chunks));
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
            return $this->getComparableString();
        }

        return $this->address;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::__toString()
     */
    public function __toString()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getBytes()
     */
    public function getBytes()
    {
        if ($this->bytes === null) {
            $this->bytes = array_map(
                function ($chunk) {
                    return (int) $chunk;
                },
                explode('.', $this->address)
            );
        }

        return $this->bytes;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getAddressType()
     */
    public function getAddressType()
    {
        return Type::T_IPv4;
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getRangeType()
     */
    public function getRangeType()
    {
        if ($this->rangeType === null) {
            // RFC 5735
            switch (true) {
                // 0.0.0.0/32
                case $this->address === '0.0.0.0':
                    $this->rangeType = RangeType::T_UNSPECIFIED;
                    break;
                // 0.0.0.0/8 - Source hosts on "this" network
                case strpos($this->address, '0.') === 0:
                    $this->rangeType = RangeType::T_THISNETWORK;
                    break;
                // 10.0.0.0/8
                case strpos($this->address, '10.') === 0:
                    $this->rangeType = RangeType::T_PRIVATENETWORK;
                    break;
                // 127.0.0.0/8 - Ordinarily implemented using only 127.0.0.1/32
                case strpos($this->address, '127.') === 0:
                    $this->rangeType = RangeType::T_LOOPBACK;
                    break;
                // 169.254.0.0/16
                case strpos($this->address, '169.254.') === 0:
                    $this->rangeType = RangeType::T_LINKLOCAL;
                    break;
                // 172.16.0.0/12
                case $this->matches(Subnet::fromString('172.16.0.0/12')):
                    $this->rangeType = RangeType::T_PRIVATENETWORK;
                    break;
                // 192.0.0.0/24 - Reserved for IETF protocol assignments
                case strpos($this->address, '192.0.0.') === 0:
                    $this->rangeType = RangeType::T_RESERVED;
                    break;
                // 192.0.2.0/24 - Assigned as "TEST-NET-1" for use in documentation and example code
                case strpos($this->address, '192.0.2.') === 0:
                    $this->rangeType = RangeType::T_RESERVED;
                    break;
                // 192.88.99.0/24 - 6to4 relay anycast addresses
                case strpos($this->address, '192.88.99.') === 0:
                    $this->rangeType = RangeType::T_ANYCASTRELAY;
                    break;
                // 192.168.0.0/16
                case strpos($this->address, '192.168.') === 0:
                    $this->rangeType = RangeType::T_PRIVATENETWORK;
                    break;
                // 198.18.0.0/15 - For use in benchmark tests of network interconnect devices
                case $this->matches(Subnet::fromString('198.18.0.0/15')):
                    $this->rangeType = RangeType::T_RESERVED;
                    break;
                // 198.51.100.0/24 - Assigned as "TEST-NET-2" for use in documentation and example code
                case strpos($this->address, '198.51.100.') === 0:
                    $this->rangeType = RangeType::T_RESERVED;
                    break;
                // 203.0.113.0/24 - Assigned as "TEST-NET-3" for use in documentation and example code.
                case strpos($this->address, '203.0.113.') === 0:
                    $this->rangeType = RangeType::T_RESERVED;
                    break;
                // 255.255.255.255/32
                case $this->address === '255.255.255.255':
                    $this->rangeType = RangeType::T_LIMITEDBROADCAST;
                    break;
                // 224.0.0.0/4 - Multicast address assignments
                case $this->matches(Subnet::fromString('224.0.0.0/4')):
                    $this->rangeType = RangeType::T_MULTICAST;
                    break;
                // 240.0.0.0/4 - Reserved for future use
                case $this->matches(Subnet::fromString('240.0.0.0/4')):
                    $this->rangeType = RangeType::T_RESERVED;
                    break;
                default:
                    $this->rangeType = RangeType::T_PUBLIC;
                    break;
            }
        }

        return $this->rangeType;
    }

    /**
     * Create an IPv6 representation of this address.
     *
     * @return IPv6
     */
    public function toIPv6()
    {
        $myBytes = $this->getBytes();

        return IPv6::fromString('2002:'.sprintf('%02x', $myBytes[0]).sprintf('%02x', $myBytes[1]).':'.sprintf('%02x', $myBytes[2]).sprintf('%02x', $myBytes[3]).'::');
    }

    /**
     * {@inheritdoc}
     *
     * @see AddressInterface::getComparableString()
     */
    public function getComparableString()
    {
        if ($this->comparableString === null) {
            $chunks = array();
            foreach ($this->getBytes() as $byte) {
                $chunks[] = sprintf('%03d', $byte);
            }
            $this->comparableString = implode('.', $chunks);
        }

        return $this->comparableString;
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
}
