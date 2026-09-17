<?php

namespace IPLib\Test\Addresses;

use IPLib\Address\AssignedRange;
use IPLib\Range\Type as RangeType;
use IPLib\Test\TestCase;

class ReservedRangesTest extends TestCase
{
    /**
     * @return array{class-string<\IPLib\Address\AddressInterface>}[]
     */
    public function addressClassProvider()
    {
        return array(
            array('IPLib\Address\IPv4'),
            array('IPLib\Address\IPv6'),
        );
    }

    /**
     * @dataProvider addressClassProvider
     *
     * @param class-string<\IPLib\Address\AddressInterface> $addressClass
     *
     * @return void
     */
    public function testReservedRanges($addressClass)
    {
        $reservedRanges = $addressClass::getReservedRanges();
        static::assertNotEmpty($reservedRanges);
        foreach ($reservedRanges as $reservedRange) {
            static::assertInstanceOf('IPLib\Address\AssignedRange', $reservedRange);
            $this->checkAssignedRange($reservedRange, $addressClass);
        }
        static::assertSame($reservedRanges, $addressClass::getReservedRanges(), 'The reserved ranges should be cached');
    }

    /**
     * @param \IPLib\Address\AssignedRange $assignedRange
     * @param class-string<\IPLib\Address\AddressInterface> $addressClass
     *
     * @return void
     */
    private function checkAssignedRange(AssignedRange $assignedRange, $addressClass)
    {
        $range = $assignedRange->getRange();
        static::assertInstanceOf('IPLib\Range\RangeInterface', $range);
        static::assertInstanceOf($addressClass, $range->getStartAddress());
        $type = $assignedRange->getType();
        static::assertMatchRegExp('/^(?!Unknown type)/', RangeType::getName($type), "{$range} has an unknown type");
        foreach ($assignedRange->getExceptions() as $exception) {
            static::assertInstanceOf('IPLib\Address\AssignedRange', $exception);
            $exceptionRange = $exception->getRange();
            static::assertTrue($range->containsRange($exceptionRange), "{$exceptionRange} should be contained in {$range}");
            static::assertNotSame($type, $exception->getType(), "{$exceptionRange} has the same type as {$range}");
            static::assertSame($exception->getType(), $assignedRange->getAddressType($exceptionRange->getStartAddress()));
            $this->checkAssignedRange($exception, $addressClass);
        }
    }
}
