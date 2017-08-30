<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;

class BoundariesTest extends \PHPUnit_Framework_TestCase
{
    public function boundariesProvider()
    {
        return array(
            array('1.2.3.4', '1.2.3.4', '1.2.3.4'),
            array('1.2.3.*', '1.2.3.0', '1.2.3.255'),
            array('1.2.*.*', '1.2.0.0', '1.2.255.255'),
            array('1.*.*.*', '1.0.0.0', '1.255.255.255'),
            array('*.*.*.*', '0.0.0.0', '255.255.255.255'),
            array('1.2.3.4/0', '0.0.0.0', '255.255.255.255'),
            array('1.2.3.4/8', '1.0.0.0', '1.255.255.255'),
            array('1.2.3.4/16', '1.2.0.0', '1.2.255.255'),
            array('1.2.3.4/24', '1.2.3.0', '1.2.3.255'),
            array('1.2.3.4/32', '1.2.3.4', '1.2.3.4'),
        );
    }

    /**
     * @dataProvider boundariesProvider
     */
    public function testBoundaries($rangeString, $startAddressString, $endAddressString)
    {
        $range = Factory::rangeFromString($rangeString);
        $this->assertInstanceOf('IPLib\Range\RangeInterface', $range);
        $this->assertSame((string) $range->getStartAddress(), $startAddressString, "Checking start address of {$rangeString}");
        $this->assertSame((string) $range->getEndAddress(), $endAddressString, "Checking end address of {$rangeString}");
    }
}
