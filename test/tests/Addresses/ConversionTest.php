<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class ConversionTest extends TestCase
{
    /**
     * @return array{string}[]
     */
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
     *
     * @return void
     */
    public function test6to4($address)
    {
        $ipV4 = Factory::addressFromString($address);
        static::assertInstanceOf('IPLib\Address\IPv4', $ipV4, "'{$address}' has been detected as an invalid IP, but it should be valid");
        $ipV6 = $ipV4->toIPv6();
        $ipV4back = $ipV6->toIPv4();
        static::assertNotNull($ipV4back, "'{$address}' has been converted to '" . $ipV6->toString() . "', but it coulnd't be converted back to IPv4");
        static::assertSame($address, $ipV4back->toString());
    }

    /**
     * @return array{string}[]
     */
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
     *
     * @return void
     */
    public function testIPv4MappedAddress($address)
    {
        $ipV4 = Factory::addressFromString($address);
        static::assertNotNull($ipV4, "'{$address}' has been detected as an invalid IP, but it should be valid");
        static::assertInstanceOf('IPLib\Address\IPv4', $ipV4);
        $ipV6 = $ipV4->toIPv6IPv4Mapped();
        static::assertInstanceOf('IPLib\Address\IPv6', $ipV6);
        $ipV4back = $ipV6->toIPv4();
        static::assertInstanceOf('IPLib\Address\IPv4', $ipV4back, "'{$address}' has been converted to '" . $ipV6->toString() . "', but it coulnd't be converted back to IPv4");
        static::assertSame($address, (string) $ipV4back->toString());
    }
}
