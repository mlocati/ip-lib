<?php

namespace IPLib\Range;

use IPLib\Address\AddressInterface;
use IPLib\Factory;

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
     * @see RangeInterface::contains()
     */
    public function contains(AddressInterface $address)
    {
        $result = false;
        if ($address->getAddressType() === $this->getAddressType()) {
            $cmp = $address->getComparableString();
            $from = $this->getComparableStartString();
            if (strcmp($cmp, $from) >= 0) {
                $to = $this->getComparableEndString();
                if (strcmp($cmp, $to) <= 0) {
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
