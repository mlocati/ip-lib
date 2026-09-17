<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class AddTest extends TestCase
{
    /**
     * @return array{string, string, string|null}[]
     */
    public function provideCases()
    {
        return array(
            array('0.0.0.0', '::', null),
            array('0.0.0.0', '0.0.0.0', '0.0.0.0'),
            array('0.0.0.1', '0.0.0.1', '0.0.0.2'),
            array('0.0.1.0', '0.0.1.0', '0.0.2.0'),
            array('0.0.0.255', '0.0.0.1', '0.0.1.0'),
            array('0.0.0.255', '0.0.0.255', '0.0.1.254'),
            array('1.2.3.4', '10.0.0.0', '11.2.3.4'),
            array('127.2.3.4', '127.255.0.0', '255.1.3.4'),
            array('127.3.3.4', '127.255.0.0', '255.2.3.4'),
            array('127.127.3.4', '128.255.0.0', null),
            array('255.255.255.254', '0.0.0.1', '255.255.255.255'),
            array('255.255.255.255', '0.0.0.1', null),
            array('255.255.255.255', '255.255.255.255', null),

            array('::', '127.0.0.1', null),
            array('::', '::', '::'),
            array('::1', '::', '::1'),
            array('::1', '::1', '::2'),
            array('::1', '::1:0', '::1:1'),
            array('::1', '::10', '::11'),
            array('::a', '::a', '::14'),
            array('::fffe', '::1', '::ffff'),
            array('::ffff', '::1', '::1:0'),
            array('::ffff', '::ffff', '::1:fffe'),
            array('ffff::', '::1', 'ffff::1'),
            array('fffe::', '1::', 'ffff::'),
            array('ffff::', '1::', null),
        );
    }

    /**
     * @dataProvider provideCases
     *
     * @param string $addressA
     * @param string $addressB
     * @param string|null $expectedSum
     *
     * @return void
     */
    public function testAdd($addressA, $addressB, $expectedSum)
    {
        $ipA = Factory::parseAddressString($addressA);
        static::assertNotNull($ipA, "'{$addressA}' has been detected as an invalid IP, but it should be valid");
        $ipB = Factory::parseAddressString($addressB);
        static::assertNotNull($ipB, "'{$addressB}' has been detected as an invalid IP, but it should be valid");
        if ($expectedSum === null) {
            static::assertNull($ipA->add($ipB));
            static::assertNull($ipB->add($ipA));
        } else {
            static::assertSame($expectedSum, (string) $ipA->add($ipB));
            static::assertSame($expectedSum, (string) $ipB->add($ipA));
        }
    }
}
