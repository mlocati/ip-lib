<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class AtOffsetTest extends TestCase
{
    public function atOffsetProvider()
    {
        $result = array(
            array('0.0.0.1', 500, '0.0.1.245'),
            array('0.0.0.2', -500, ''),
            array('1.2.3.4', 1024, '1.2.7.4'),
            array('1.2.3.0', -1024, '1.1.255.0'),
            array('1.255.255.253', 3, '2.0.0.0'),
            array('255.255.255.253', 3, ''),
            array('255.255.255.253', null, ''),
            array('::1', 65536, '::1:1'),
            array('::', -500, ''),
            array('::fffc', 3, '::ffff'),
            array('::ffff:ffff:ffff:0', -3, '::ffff:ffff:fffe:fffd'),
            array('::ffff:ffff:ffff:fffd', 3, '0:0:0:1::'),
            array('0:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 3, '1::2'),
            array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffd', 3, ''),
            array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffd', null, ''),
            array('0.0.0.0', -1, ''),
            array('0.0.0.0', 0, '0.0.0.0'),
            array('0.0.0.0', 1, '0.0.0.1'),
            array('::', -1, ''),
            array('::', 0, '::'),
            array('::', 1, '::1'),
            array('0.0.0.0', 0x7fffffff, '127.255.255.255'),
            array('0.0.0.1', 0x7fffffff, '128.0.0.0'),
            array('::', 0x7fffffff, '::7fff:ffff'),
            array('::1', 0x7fffffff, '::8000:0'),
        );
        if (PHP_INT_SIZE > 4) {
            $result = array_merge($result, array(
                array('0.0.0.0', PHP_INT_MAX, ''),
                array('0.0.0.0', 0xffffffff, '255.255.255.255'),
                array('0.0.0.0', 0x100000000, ''),
                array('::', 0xffffffff, '::ffff:ffff'),
                array('::1', 0x100000000, '::1:0:1'),
            ));
        }

        return $result;
    }

    /**
     * @dataProvider atOffsetProvider
     *
     * @param string $addressString
     * @param int|mixed $n
     * @param string $expected
     */
    public function testAtOffset($addressString, $n, $expected)
    {
        $address = Factory::addressFromString($addressString);
        $this->assertInstanceof(
            'IPLib\Address\AddressInterface',
            $address,
            "Checking that {$addressString} is a valid address"
        );

        $next = $address->getAddressAtOffset($n);

        $this->assertSame(
            $expected,
            (string) $next,
            "Checking the address {$addressString} " . (is_int($n) ? (($n > 0 ? '+' : '-') . ' ' . abs($n)) : gettype($n))
        );

        if ($next !== null && is_int($n)) {
            $this->assertSame(
                $addressString,
                (string) $next->getAddressAtOffset(-$n),
                "Checking the address after the nth address before the nth {$addressString}"
            );
        }
    }
}
