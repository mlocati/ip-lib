<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class NonDecimalRangesTest extends TestCase
{
    /**
     * @return array{string, bool, 2?: string|null}[]
     */
    public function casesProvider()
    {
        return array(
            array('07.0.0.*', false, '7.0.0.*'),
            array('07.0.0.*', true, '7.0.0.*'),
            array('08.0.0.*', false, '8.0.0.*'),
            array('08.0.0.*', true),
            array('010.0.0.*', false, '10.0.0.*'),
            array('010.0.0.*', true, '8.0.0.*'),
            array('00000000000000000000000000000000000000010.0.0.*', false, '10.0.0.*'),
            array('00000000000000000000000000000000000000010.0.0.*', true, '8.0.0.*'),
            array('07.0.0.123/32', false, '7.0.0.123/32'),
            array('07.0.0.123/32', true, '7.0.0.123/32'),
            array('08.0.0.123/32', false, '8.0.0.123/32'),
            array('08.0.0.123/32', true),
            array('010.0.0.123/32', false, '10.0.0.123/32'),
            array('010.0.0.123/32', true, '8.0.0.123/32'),
            array('0x10.0.0.123/32', false),
            array('0x10.0.0.123/32', true, '16.0.0.123/32'),
            array('000000000000000000000000000000000000000x10.0.0.123/32', true, '16.0.0.123/32'),
            array('0x00000000000000000000000000000000000000010.0.0.123/32', true, '16.0.0.123/32'),
            array('000000000000000000000000000000000000000x00000000000000000000000000000000000000010.0.0.123/32', true, '16.0.0.123/32'),
            array('07.0.0.123', false, '7.0.0.123'),
            array('07.0.0.123', true, '7.0.0.123'),
            array('08.0.0.123', false, '8.0.0.123'),
            array('08.0.0.123', true),
            array('010.0.0.123', false, '10.0.0.123'),
            array('010.0.0.123', true, '8.0.0.123'),
            array('0x10.0.0.123', false),
            array('0x10.0.0.123', true, '16.0.0.123'),
            array('010.0XfA.000000100.123', true, '8.250.64.123'),
            array('000000000000000000000000000000000000000x10.0.0.123', true, '16.0.0.123'),
            array('0x00000000000000000000000000000000000000010.0.0.123', true, '16.0.0.123'),
            array('000000000000000000000000000000000000000x00000000000000000000000000000000000000010.0.0.123', true, '16.0.0.123'),
        );
    }

    /**
     * @dataProvider casesProvider
     *
     * @param string $input
     * @param bool $parseNonDecimal
     * @param string|null $expectedStringRepresentation
     *
     * @return void
     */
    public function testCases($input, $parseNonDecimal, $expectedStringRepresentation = null)
    {
        $range = Factory::rangeFromString($input, $parseNonDecimal);
        if ($expectedStringRepresentation === null) {
            $this->assertNull($range);
        } else {
            $this->assertInstanceOf('\IPLib\Range\RangeInterface', $range);
            $this->assertSame($expectedStringRepresentation, (string) $range->toString());
        }
    }
}
