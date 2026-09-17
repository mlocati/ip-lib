<?php

namespace IPLib\Test\Addresses;

use IPLib\Address\IPv4;
use IPLib\Address\IPv6;
use IPLib\Factory;
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
            array("127.0.0.1\n"),
            array("::1\n"),
            array("1:2:3:4:5:6:7:8\n"),
            array("::ffff:127.0.0.1\n"),
            // Wrong number of bytes/words
            array(array(1, 2, 3)),
            array(array(1, 2, 3, 4, 5)),
            array(array_fill(0, 15, 0)),
            array(array_fill(0, 17, 0)),
            // Right number of bytes/words, but with invalid values
            array(array(1, 2, 3, 256)),
            array(array(1, 2, 3, -1)),
            array(array(1, 2, 3, '4')),
            array(array_merge(array_fill(0, 15, 0), array(256))),
            array(array_merge(array_fill(0, 15, 0), array(-1))),
            array(array_merge(array_fill(0, 15, 0), array('0'))),
            array(array_merge(array_fill(0, 7, 0), array(0x10000))),
            array(array_merge(array_fill(0, 7, 0), array(-1))),
            array(array_merge(array_fill(0, 7, 0), array('a'))),
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

        static::assertNull(IPv4::fromString($str), "'{$str}' has been detected as a valid IPv4 address, but it shouldn't");
        static::assertNull(IPv6::fromString($str), "'{$str}' has been detected as a valid IPv6 address, but it shouldn't");

        static::assertNull(IPv4::fromBytes($arr), "'{$str}' has been detected as a valid IPv4 address, but it shouldn't");
        static::assertNull(IPv6::fromBytes($arr), "'{$str}' has been detected as a valid IPv6 address, but it shouldn't");

        static::assertNull(IPv6::fromWords($arr), "'{$str}' has been detected as a valid IPv6 address, but it shouldn't");

        static::assertNull(Factory::addressFromBytes($arr), "'{$str}' has been detected as a valid address, but it shouldn't");
    }
}
