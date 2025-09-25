<?php

namespace IPLib\Test\Addresses;

use IPLib\Address\IPv4;
use IPLib\Address\IPv6;
use IPLib\Test\TestCase;

class InvalidTest extends TestCase
{
    /**
     * @return array{string|mixed}[]
     */
    public function invalidAddressesProvider()
    {
        return array(
            array(''),
            array(0),
            array(null),
            array(false),
            array(array()),
            array('127'),
            array('127.0'),
            array('127.0.0'),
            array('127.0.0.0.0'),
            array('127.0.0.300'),
            array('127.0.00 .1'),
            array('127.0. 0.1'),
            array('127. '),
            array(':::1'),
            array('::1::'),
            array('1::1::1'),
            array('1.1.1.1/8'),
            array('1.-.1.1'),
            array('00000::1'),
            array('z::'),
        );
    }

    /**
     * @dataProvider invalidAddressesProvider
     *
     * @param string|mixed $address
     *
     * @return void
     */
    public function testInvalidAddresses($address)
    {
        // @phpstan-ignore argument.type
        set_error_handler(function () {}, -1);
        // @phpstan-ignore cast.string
        $str = (string) $address;
        $arr = (array) $address;
        restore_error_handler();

        $this->assertNull(IPv4::fromString($str), "'{$str}' has been detected as a valid IPv4 address, but it shouldn't");
        $this->assertNull(IPv6::fromString($str), "'{$str}' has been detected as a valid IPv6 address, but it shouldn't");

        $this->assertNull(IPv4::fromBytes($arr), "'{$str}' has been detected as a valid IPv4 address, but it shouldn't");
        $this->assertNull(IPv6::fromBytes($arr), "'{$str}' has been detected as a valid IPv6 address, but it shouldn't");

        $this->assertNull(IPv6::fromWords($arr), "'{$str}' has been detected as a valid IPv6 address, but it shouldn't");
    }
}
