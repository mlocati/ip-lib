<?php

namespace IPLib\Range;

use IPLib\Address\AddressInterface;
use IPLib\Address\IPv4;
use IPLib\Address\IPv6;
use IPLib\Address\Type as AddressType;
use IPLib\Factory;

/**
 * Represents an address range in pattern format (only ending asterisks are supported).
 *
 * @example 127.0.*.*
 * @example ::/8
 */
class Pattern implements RangeInterface
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
     * Number of ending asterisks.
     *
     * @var int
     */
    protected $asterisksCount;

    /**
     * The type of the range of this IP range.
     *
     * @var int|null|false false if this range crosses multiple range types, null if yet to be determined
     */
    protected $rangeType;

    /**
     * Initializes the instance.
     *
     * @param AddressInterface $fromAddress
     * @param AddressInterface $toAddress
     * @param int $asterisksCount
     */
    public function __construct(AddressInterface $fromAddress, AddressInterface $toAddress, $asterisksCount)
    {
        $this->fromAddress = $fromAddress;
        $this->toAddress = $toAddress;
        $this->asterisksCount = $asterisksCount;
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
        if (is_string($range) && strpos($range, '*') !== false) {
            if ($range === '*.*.*.*') {
                $result = new static(IPv4::fromString('0.0.0.0'), IPv4::fromString('255.255.255.255'), 4);
            } elseif (strpos($range, '.') !== false && preg_match('/^[^*]+((?:\.\*)+)$/', $range, $matches)) {
                $asterisksCount = strlen($matches[1]) >> 1;
                if ($asterisksCount > 0) {
                    $missingDots = 3 - substr_count($range, '.');
                    if ($missingDots > 0) {
                        $range .= str_repeat('.*', $missingDots);
                        $asterisksCount += $missingDots;
                    }
                }
                $fromAddress = IPv4::fromString(str_replace('*', '0', $range));
                if ($fromAddress !== null) {
                    $fixedBytes = array_slice($fromAddress->getBytes(), 0, -$asterisksCount);
                    $otherBytes = array_fill(0, $asterisksCount, 255);
                    $toAddress = IPv4::fromBytes(array_merge($fixedBytes, $otherBytes));
                    $result = new static($fromAddress, $toAddress, $asterisksCount);
                }
            } elseif ($range === '*:*:*:*:*:*:*:*') {
                $result = new static(IPv6::fromString('::'), IPv6::fromString('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'), 8);
            } elseif (strpos($range, ':') !== false && preg_match('/^[^*]+((?::\*)+)$/', $range, $matches)) {
                $asterisksCount = strlen($matches[1]) >> 1;
                $fromAddress = IPv6::fromString(str_replace('*', '0', $range));
                if ($fromAddress !== null) {
                    $fixedWords = array_slice($fromAddress->getWords(), 0, -$asterisksCount);
                    $otherWords = array_fill(0, $asterisksCount, 0xffff);
                    $toAddress = IPv6::fromWords(array_merge($fixedWords, $otherWords));
                    $result = new static($fromAddress, $toAddress, $asterisksCount);
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Range\RangeInterface::toString()
     */
    public function toString($long = false)
    {
        switch (true) {
            case $this->fromAddress instanceof \IPLib\Address\IPv4:
                $chunks = explode('.', $this->fromAddress->toString());
                $chunks = array_slice($chunks, 0, -$this->asterisksCount);
                $chunks = array_pad($chunks, 4, '*');
                $result = implode('.', $chunks);
                break;
            case $this->fromAddress instanceof \IPLib\Address\IPv6:
                if ($long) {
                    $chunks = explode(':', $this->fromAddress->toString(true));
                    $chunks = array_slice($chunks, 0, -$this->asterisksCount);
                    $chunks = array_pad($chunks, 8, '*');
                    $result = implode(':', $chunks);
                } else {
                    $chunks = explode(':', $this->toAddress->toString(false));
                    $chunkCount = count($chunks);
                    $chunks = array_slice($chunks, 0, -$this->asterisksCount);
                    $chunks = array_pad($chunks, $chunkCount, '*');
                    $result = implode(':', $chunks);
                }
                break;
            default:
                throw new \Exception('@todo'); // @codeCoverageIgnore
        }

        return $result;
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
            $addressType = $this->getAddressType();
            if ($addressType === AddressType::T_IPv6 && Subnet::get6to4()->containsRange($this)) {
                $this->rangeType = Factory::rangeFromBoundaries($this->fromAddress->toIPv4(), $this->toAddress->toIPv4())->getRangeType();
            } else {
                switch ($addressType) {
                    case AddressType::T_IPv4:
                        $defaultType = IPv4::getDefaultReservedRangeType();
                        $reservedRanges = IPv4::getReservedRanges();
                        break;
                    case AddressType::T_IPv6:
                        $defaultType = IPv6::getDefaultReservedRangeType();
                        $reservedRanges = IPv6::getReservedRanges();
                        break;
                    default:
                        throw new \Exception('@todo'); // @codeCoverageIgnore
                }
                $rangeType = null;
                foreach ($reservedRanges as $reservedRange) {
                    $rangeType = $reservedRange->getRangeType($this);
                    if ($rangeType !== null) {
                        break;
                    }
                }
                $this->rangeType = $rangeType === null ? $defaultType : $rangeType;
            }
        }

        return $this->rangeType === false ? null : $this->rangeType;
    }

    /**
     * {@inheritdoc}
     *
     * @see RangeInterface::contains()
     */
    public function contains(AddressInterface $address)
    {
        $result = false;
        if ($address->getAddressType() === $this->getAddressType()) {
            $cmp = $address->getComparableString();
            $from = $this->getComparableStartString();
            if ($cmp >= $from) {
                $to = $this->getComparableEndString();
                if ($cmp <= $to) {
                    $result = true;
                }
            }
        }

        return $result;
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
            $myStart = $this->getComparableStartString();
            $itsStart = $range->getComparableStartString();
            if ($itsStart >= $myStart) {
                $myEnd = $this->getComparableEndString();
                $itsEnd = $range->getComparableEndString();
                if ($itsEnd <= $myEnd) {
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
