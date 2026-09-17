<?php

namespace IPLib\Test\Helpers;

use IPLib\Factory;

class FactoryTestWrapper extends Factory
{
    /**
     * Expose the protected rangeFromBoundaryAddresses() method.
     *
     * @param \IPLib\Address\AddressInterface|null $from
     * @param \IPLib\Address\AddressInterface|null $to
     *
     * @return \IPLib\Range\RangeInterface|null
     */
    public static function callRangeFromBoundaryAddresses($from = null, $to = null)
    {
        return static::rangeFromBoundaryAddresses($from, $to);
    }
}
