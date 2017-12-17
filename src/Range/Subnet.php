<?php

namespace IPLib\Range;

use IPLib\Address\AddressInterface;
use IPLib\Address\IPv4;
use IPLib\Address\IPv6;
use IPLib\Address\Type as AddressType;
use IPLib\Factory;
use IPLib\Range\Type as RangeType;

/**
 * Represents an address range in subnet format (eg CIDR).
 *
 * @example 127.0.0.1/32
 * @example ::/8
 */
class Subnet implements RangeInterface
{
    /**
     * Starting address of the range.
     *
     * @var AddressInterface
     */
    protected $fromAddress;

    /**
     * Final address of the range.
     *
     * @var AddressInterface
     */
    protected $toAddress;

    /**
     * Number of the same bits of the range.
     *
     * @var int
     */
    protected $networkPrefix;

    /**
     * The type of the range of this IP range.
     *
     * @var int|null
     */
    protected $rangeType;

    /**
     * Initializes the instance.
     *
     * @param AddressInterface $fromAddress
     * @param AddressInterface $toAddress
     * @param int $networkPrefix
     */
    protected function __construct(AddressInterface $fromAddress, AddressInterface $toAddress, $networkPrefix)
    {
        $this->fromAddress = $fromAddress;
        $this->toAddress = $toAddress;
        $this->networkPrefix = $networkPrefix;
    }

    /**
     * Try get the range instance starting from its string representation.
     *
     * @param string|mixed $range
     *
     * @return static|null
     */
    public static function fromString($range)
    {
        $result = null;
        if (is_string($range)) {
            $parts = explode('/', $range);
            if (count($parts) === 2) {
                $address = Factory::addressFromString($parts[0]);
                if ($address !== null) {
                    if (preg_match('/^[0-9]{1,9}$/', $parts[1])) {
                        $networkPrefix = (int) $parts[1];
                        if ($networkPrefix >= 0) {
                            $addressBytes = $address->getBytes();
                            $totalBytes = count($addressBytes);
                            $numDifferentBits = $totalBytes * 8 - $networkPrefix;
                            if ($numDifferentBits >= 0) {
                                $numSameBytes = $networkPrefix >> 3;
                                $sameBytes = array_slice($addressBytes, 0, $numSameBytes);
                                $differentBytesStart = ($totalBytes === $numSameBytes) ? array() : array_fill(0, $totalBytes - $numSameBytes, 0);
                                $differentBytesEnd = ($totalBytes === $numSameBytes) ? array() : array_fill(0, $totalBytes - $numSameBytes, 255);
                                $startSameBits = $networkPrefix % 8;
                                if ($startSameBits !== 0) {
                                    $varyingByte = $addressBytes[$numSameBytes];
                                    $differentBytesStart[0] = $varyingByte & bindec(str_pad(str_repeat('1', $startSameBits), 8, '0', STR_PAD_RIGHT));
                                    $differentBytesEnd[0] = $differentBytesStart[0] + bindec(str_repeat('1', 8 - $startSameBits));
                                }
                                $result = new static(
                                    Factory::addressFromBytes(array_merge($sameBytes, $differentBytesStart)),
                                    Factory::addressFromBytes(array_merge($sameBytes, $differentBytesEnd)),
                                    $networkPrefix
                                );
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::toString()
     */
    public function toString($long = false)
    {
        return $this->fromAddress->toString($long).'/'.$this->networkPrefix;
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::__toString()
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::getAddressType()
     */
    public function getAddressType()
    {
        return $this->fromAddress->getAddressType();
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::getRangeType()
     */
    public function getRangeType()
    {
        if ($this->rangeType === null) {
            switch ($this->getAddressType()) {
                case AddressType::T_IPv4:
                    // Default is public
                    $this->rangeType = RangeType::T_PUBLIC;
                    $reservedRanges = IPv4::getReservedRanges();
                    break;
                case AddressType::T_IPv6:
                    if (self::fromString('2002::/16')->containsRange($this)) {
                        $this->rangeType = Factory::rangeFromBoundaries($this->fromAddress->toIPv4(), $this->toAddress->toIPv4())->getRangeType();

                        return $this->rangeType;
                    } else {
                        // Default is public
                        $this->rangeType = RangeType::T_RESERVED;
                        $reservedRanges = IPv6::getReservedRanges();
                    }
                    break;
            }

            // Check if range is contained within an RFC subnet
            foreach ($reservedRanges as $reservedRange) {
                if ($reservedRange['range']->containsRange($this)) {
                    $this->rangeType = $reservedRange['type'];
                    break;
                }
            }

            // Check if public/reserved (default) range contains an RFC subnet
            if ($this->rangeType === RangeType::T_PUBLIC || $this->rangeType === RangeType::T_RESERVED) {
                foreach ($reservedRanges as $reservedRange) {
                    if ($this->containsRange($reservedRange['range'])) {
                        if ($this->rangeType !== $reservedRange['type']) {
                            // RFC 5735 specifies that 255.255.255.255/32 is excluded from 240.0.0.0/4 and 224.0.0.0/4
                            if ($this->getAddressType() === AddressType::T_IPv4 &&
                                ($reservedRange['type'] === RangeType::T_LIMITEDBROADCAST ||
                                 $reservedRange['type'] === RangeType::T_UNSPECIFIED)) {
                                continue;
                            }

                            // :: unspecified exists within ::/8 reserved
                            // ::1/128 loopback exists within ::/8 reserved
                            if ($this->getAddressType() === AddressType::T_IPv6 &&
                                ($reservedRange['type'] === RangeType::T_UNSPECIFIED ||
                                 $reservedRange['type'] === RangeType::T_LOOPBACK)) {
                                continue;
                            }
                            $this->rangeType = null;
                        }
                    }
                }
            }
        }

        return $this->rangeType;
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::contains()
     */
    public function contains(AddressInterface $address)
    {
        return $this->containsRange(Single::fromAddress($address));
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::containsRange()
     */
    public function containsRange(RangeInterface $range)
    {
        $result = false;
        if ($range->getAddressType() === $this->getAddressType()) {
            $cmpLower = $range->getComparableStartString();
            $cmpHigher = $range->getComparableEndString();
            $from = $this->getComparableStartString();
            if (strcmp($cmpLower, $from) >= 0) {
                $to = $this->getComparableEndString();
                if (strcmp($cmpHigher, $to) <= 0) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::getStartAddress()
     */
    public function getStartAddress()
    {
        return $this->fromAddress;
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::getEndAddress()
     */
    public function getEndAddress()
    {
        return $this->toAddress;
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::getComparableStartString()
     */
    public function getComparableStartString()
    {
        return $this->fromAddress->getComparableString();
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::getComparableEndString()
     */
    public function getComparableEndString()
    {
        return $this->toAddress->getComparableString();
    }
}
