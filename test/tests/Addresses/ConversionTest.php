<?php

namespace IPLib\Test\Addresses;

use IPLib\Address\IPv4;
use IPLib\Factory;
use IPLib\Test\TestCase;

class ConversionTest extends TestCase
{
    public function get6to4TestCases()
    {
        return array(
            array('0.0.0.0'),
            array('127.0.0.1'),
            array('10.20.30.40'),
            array('255.255.255.255'),
        );
    }

    /**
     * @dataProvider get6to4TestCases
     *
     * @param string $address
     */
    public function test6to4($address)
    {
        $ipV4 = Factory::addressFromString($address);
        $this->assertNotNull($ipV4, "'{$address}' has been detected as an invalid IP, but it should be valid");
        $this->assertInstanceOf('IPLib\Address\IPv4', $ipV4);
        // @var \IPLib\Address\IPv4 $ipV4
        $ipV6 = $ipV4->toIPv6();
        $this->assertNotNull($ipV6, "'{$address}' couldn't be converted to IPv6");
        $ipV4back = $ipV6->toIPv4();
        $this->assertNotNull($ipV4, "'{$address}' has been converted to '" . $ipV6->toString() . "', but it coulnd't be converted back to IPv4");
        $this->assertSame($address, $ipV4back->toString());
    }

    public function getIPv4MappedAddessTestCases()
    {
        return array(
            array('0.0.0.0'),
            array('127.0.0.1'),
            array('10.20.30.40'),
            array('255.255.255.255'),
        );
    }

    /**
     * @dataProvider getIPv4MappedAddessTestCases
     *
     * @param string $address
     */
    public function testIPv4MappedAddress($address)
    {
        /* @var $ipV4 IPv4 */
        $ipV4 = Factory::addressFromString($address);
        $this->assertNotNull($ipV4, "'{$address}' has been detected as an invalid IP, but it should be valid");
        $this->assertInstanceOf('IPLib\Address\IPv4', $ipV4);
        $ipV6 = $ipV4->toIPv6IPv4Mapped();
        $this->assertNotNull($ipV6);
        $this->assertInstanceOf('IPLib\Address\IPv6', $ipV6);
        $ipV4back = $ipV6->toIPv4();
        $this->assertNotNull($ipV4, "'{$address}' has been converted to '" . $ipV6->toString() . "', but it coulnd't be converted back to IPv4");
        $this->assertSame($address, (string) $ipV4back->toString());
    }
}
