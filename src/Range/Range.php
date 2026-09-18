<?php

namespace IPLib\Range;

use IPLib\Address\AddressInterface;
use IPLib\Factory;
use IPLib\Service\BinaryMath;
use OutOfBoundsException;

/**
 * Represents an address range with arbitrary inclusive boundaries.
 *
 * @example 192.168.0.10-192.168.0.20
 * @example 2001:db8::1-2001:db8::ff
 *
 * @phpstan-consistent-constructor
 */
class Range extends AbstractRange
{
    /**
     * Starting address of the range.
     *
     * @var \IPLib\Address\AddressInterface
     */
    protected $fromAddress;

    /**
     * Final address of the range.
     *
     * @var \IPLib\Address\AddressInterface
     */
    protected $toAddress;

    /**
     * Number of common leading bits in the boundary addresses.
     *
     * @var int
     */
    protected $networkPrefix;

    /**
     * The type of the range of this IP range.
     *
     * @var int|false|null false if this range crosses multiple range types, null if yet to be determined
     */
    protected $rangeType;

    /**
     * Initializes the instance.
     *
     * @param \IPLib\Address\AddressInterface $fromAddress
     * @param \IPLib\Address\AddressInterface $toAddress
     * @param int $networkPrefix
     */
    protected function __construct(AddressInterface $fromAddress, AddressInterface $toAddress, $networkPrefix)
    {
        $this->fromAddress = $fromAddress;
        $this->toAddress = $toAddress;
        $this->networkPrefix = $networkPrefix;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::__toString()
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Try get the range instance starting from its string representation.
     *
     * The range boundaries are inclusive and separated by a hyphen.
     *
     * @param string|mixed $range
     * @param int $flags A combination or zero or more flags
     *
     * @return static|null
     *
     * @see \IPLib\ParseStringFlag
     */
    public static function parseString($range, $flags = 0)
    {
        if (!is_string($range)) {
            return null;
        }
        $parts = explode('-', $range);
        if (count($parts) !== 2) {
            return null;
        }
        $fromAddress = Factory::parseAddressString($parts[0], $flags);
        $toAddress = Factory::parseAddressString($parts[1], $flags);

        return $fromAddress === null || $toAddress === null ? null : static::fromBoundaries($fromAddress, $toAddress);
    }

    /**
     * Create an arbitrary range from two inclusive address boundaries.
     *
     * The boundaries may be supplied in either order.
     *
     * @param \IPLib\Address\AddressInterface $fromAddress
     * @param \IPLib\Address\AddressInterface $toAddress
     *
     * @return static|null NULL if the addresses have different types
     */
    public static function fromBoundaries(AddressInterface $fromAddress, AddressInterface $toAddress)
    {
        if ($fromAddress->getAddressType() !== $toAddress->getAddressType()) {
            return null;
        }
        $fromBits = $fromAddress->getBits();
        $toBits = $toAddress->getBits();
        if (strcmp($fromBits, $toBits) > 0) {
            list($fromAddress, $toAddress, $fromBits, $toBits) = array($toAddress, $fromAddress, $toBits, $fromBits);
        }
        $networkPrefix = 0;
        $numberOfBits = strlen($fromBits);
        while ($networkPrefix < $numberOfBits && $fromBits[$networkPrefix] === $toBits[$networkPrefix]) {
            $networkPrefix++;
        }

        return new static($fromAddress, $toAddress, $networkPrefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::toString()
     */
    public function toString($long = false)
    {
        return $this->fromAddress->toString($long) . '-' . $this->toAddress->toString($long);
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getAddressType()
     */
    public function getAddressType()
    {
        return $this->fromAddress->getAddressType();
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getStartAddress()
     */
    public function getStartAddress()
    {
        return $this->fromAddress;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getEndAddress()
     */
    public function getEndAddress()
    {
        return $this->toAddress;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getComparableStartString()
     */
    public function getComparableStartString()
    {
        return $this->fromAddress->getComparableString();
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getComparableEndString()
     */
    public function getComparableEndString()
    {
        return $this->toAddress->getComparableString();
    }

    /**
     * {@inheritdoc}
     *
     * Returns the smallest subnet containing this range. This may include addresses outside this range.
     *
     * @see \IPLib\Range\RangeInterface::asSubnet()
     */
    public function asSubnet()
    {
        return Subnet::parseString($this->fromAddress->toString() . '/' . $this->networkPrefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::asPattern()
     */
    public function asPattern()
    {
        $subnet = $this->asSubnet();
        if ($subnet->getComparableStartString() !== $this->getComparableStartString() || $subnet->getComparableEndString() !== $this->getComparableEndString()) {
            return null;
        }

        return $subnet->asPattern();
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getSubnetMask()
     */
    public function getSubnetMask()
    {
        return $this->buildSubnetMask($this->networkPrefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getReverseDNSLookupName()
     */
    public function getReverseDNSLookupName()
    {
        $result = array();
        $ranges = Factory::getRangesFromBoundaries($this->fromAddress, $this->toAddress);
        /** @var \IPLib\Range\Subnet[] $ranges */
        foreach ($ranges as $range) {
            foreach ($range->getReverseDNSLookupName() as $name) {
                $result[] = $name;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getSize()
     */
    public function getSize()
    {
        $size = $this->getExactSize();

        return is_int($size) ? $size : (float) $size;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getExactSize()
     */
    public function getExactSize()
    {
        $difference = self::subtractBits($this->toAddress->getBits(), $this->fromAddress->getBits());
        $size = self::binaryToIntegerString(BinaryMath::getInstance()->increment($difference));
        $maxInt = (string) PHP_INT_MAX;

        return strlen($size) < strlen($maxInt) || (strlen($size) === strlen($maxInt) && strcmp($size, $maxInt) <= 0) ? (int) $size : $size;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::getNetworkPrefix()
     */
    public function getNetworkPrefix()
    {
        return $this->networkPrefix;
    }

    /**
     * {@inheritdoc}
     *
     * Arbitrary boundaries are represented by the minimum set of CIDR blocks. Every returned subnet is contained in this range and has a prefix at least as large as $networkPrefix.
     *
     * @see \IPLib\Range\RangeInterface::split()
     */
    public function split($networkPrefix, $forceSubnet = false)
    {
        $networkPrefix = (int) $networkPrefix;
        if ($networkPrefix < $this->networkPrefix) {
            throw new OutOfBoundsException("The value of the \$networkPrefix parameter can't be smaller than the network prefix of the range ({$this->networkPrefix})");
        }
        $maxPrefix = $this->fromAddress::getNumberOfBits();
        if ($networkPrefix > $maxPrefix) {
            throw new OutOfBoundsException("The value of the \$networkPrefix parameter can't be larger than the maximum network prefix of the range ({$maxPrefix})");
        }
        $result = array();
        $ranges = Factory::getRangesFromBoundaries($this->fromAddress, $this->toAddress);
        /** @var \IPLib\Range\Subnet[] $ranges */
        foreach ($ranges as $range) {
            if ($range->getNetworkPrefix() < $networkPrefix) {
                foreach ($range->split($networkPrefix, true) as $smallerRange) {
                    $result[] = $smallerRange;
                }
            } else {
                $result[] = $range;
            }
        }

        return $result;
    }

    /**
     * Subtract two equally sized binary integers where $a is greater than or equal to $b.
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    private static function subtractBits($a, $b)
    {
        $borrow = 0;
        $result = '';
        for ($index = strlen($a) - 1; $index >= 0; $index--) {
            $difference = (int) $a[$index] - (int) $b[$index] - $borrow;
            if ($difference < 0) {
                $difference += 2;
                $borrow = 1;
            } else {
                $borrow = 0;
            }
            $result .= (string) $difference;
        }

        return strrev($result);
    }

    /**
     * Convert a binary integer to its decimal representation.
     *
     * @param string $bits
     *
     * @return numeric-string
     */
    private static function binaryToIntegerString($bits)
    {
        $digits = array(0);
        foreach (str_split($bits) as $bit) {
            $carry = (int) $bit;
            foreach ($digits as $index => $digit) {
                $value = $digit * 2 + $carry;
                $digits[$index] = $value % 10;
                $carry = (int) ($value / 10);
            }
            if ($carry !== 0) {
                $digits[] = $carry;
            }
        }
        $result = implode('', array_reverse($digits));
        /** @var numeric-string $result */

        return $result;
    }
}
