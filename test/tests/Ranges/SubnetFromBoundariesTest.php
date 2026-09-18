<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

class SubnetFromBoundariesTest extends TestCase
{
    /**
     * @return array{string, string, string}[]
     */
    public function subnetProvider()
    {
        return array(
            array('211.253.24.0', '211.253.27.255', '211.253.24.0/22'),
            array('1.2.3.4', '1.2.3.4', '1.2.3.4/32'),
            array('0.0.0.0', '255.255.255.255', '0.0.0.0/0'),
            array('2001:db8::', '2001:db8:0:ffff:ffff:ffff:ffff:ffff', '2001:db8::/48'),
            array('::1', '::1', '::1/128'),
        );
    }

    /**
     * @dataProvider subnetProvider
     *
     * @param string $from
     * @param string $to
     * @param string $expected
     *
     * @return void
     */
    public function testSubnet($from, $to, $expected)
    {
        $subnet = Factory::getSubnetFromBoundaries($from, $to);
        $this->assertInstanceOf('IPLib\Range\Subnet', $subnet);
        $this->assertSame($expected, (string) $subnet);
        $subnet = Factory::getSubnetFromBoundaries($to, $from);
        $this->assertInstanceOf('IPLib\Range\Subnet', $subnet);
        $this->assertSame($expected, (string) $subnet);
    }

    /**
     * @return array{string|mixed, string|mixed}[]
     */
    public function notASubnetProvider()
    {
        return array(
            // Two adjacent subnets (/12 + /13)
            array('182.208.0.0', '182.231.255.255'),
            array('1.2.3.1', '1.2.3.255'),
            array('1.2.3.0', '1.2.3.254'),
            array('2001:db8::1', '2001:db8::ffff'),
            array('127.0.0.1', '::1'),
            array('127.0.0.1', 'a'),
            array(null, '127.0.0.1'),
            array(null, null),
        );
    }

    /**
     * @dataProvider notASubnetProvider
     *
     * @param string|mixed $from
     * @param string|mixed $to
     *
     * @return void
     */
    public function testNotASubnet($from, $to)
    {
        $this->assertNull(Factory::getSubnetFromBoundaries($from, $to));
        $this->assertNull(Factory::getSubnetFromBoundaries($to, $from));
    }
}
