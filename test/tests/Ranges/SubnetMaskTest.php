<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

class SubnetMaskTest extends TestCase
{
    /**
     * @return array{string, string}[]
     */
    public function subnetMaskProvider()
    {
        return array(
            array('1.2.3.4', '255.255.255.255'),
            array('1.2.3.*', '255.255.255.0'),
            array('1.2.*.*', '255.255.0.0'),
            array('1.*.*.*', '255.0.0.0'),
            array('*.*.*.*', '0.0.0.0'),
            array('1.2.3.4/0', '0.0.0.0'),
            array('1.2.3.4/1', '128.0.0.0'),
            array('1.2.3.4/2', '192.0.0.0'),
            array('1.2.3.4/3', '224.0.0.0'),
            array('1.2.3.4/4', '240.0.0.0'),
            array('1.2.3.4/5', '248.0.0.0'),
            array('1.2.3.4/6', '252.0.0.0'),
            array('1.2.3.4/7', '254.0.0.0'),
            array('1.2.3.4/8', '255.0.0.0'),
            array('1.2.3.4/9', '255.128.0.0'),
            array('1.2.3.4/15', '255.254.0.0'),
            array('1.2.3.4/16', '255.255.0.0'),
            array('1.2.3.4/17', '255.255.128.0'),
            array('1.2.3.4/23', '255.255.254.0'),
            array('1.2.3.4/24', '255.255.255.0'),
            array('1.2.3.4/25', '255.255.255.128'),
            array('1.2.3.4/31', '255.255.255.254'),
            array('1.2.3.4/32', '255.255.255.255'),
            // No subnet mask for IPv6 ranges
            array('::', ''),
            array('::*', ''),
            array('::/1', ''),
        );
    }

    /**
     * @dataProvider subnetMaskProvider
     *
     * @param string $rangeString
     * @param string $subnetMaskString
     *
     * @return void
     */
    public function testSubnetMask($rangeString, $subnetMaskString)
    {
        $range = Factory::rangeFromString($rangeString);
        $this->assertNotNull($range);
        $this->assertInstanceOf('IPLib\Range\RangeInterface', $range);
        $subnetMask = $range->getSubnetMask();
        if ($subnetMaskString === '') {
            $this->assertNull($subnetMask);
        } else {
            $this->assertInstanceOf('IPLib\Address\AddressInterface', $subnetMask);
            $this->assertSame($subnetMaskString, (string) $subnetMask);
        }
    }
}
