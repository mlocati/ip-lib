<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\ParseStringFlag;
use IPLib\Test\TestCase;

class RangeFromAddressesTest extends TestCase
{
    /**
     * @return array{mixed}[]
     */
    public function invalidProvider()
    {
        return array(
            array(array()),
            array(array(null)),
            array(array(false)),
            array(array('')),
            array(array('1.2.3.4.5')),
            array(array('127.0.0.1', '::1')),
            array(array(Factory::parseAddressString('127.0.0.1'), '::1')),
        );
    }

    /**
     * @dataProvider invalidProvider
     *
     * @param array{mixed} $addresses
     *
     * @return void
     */
    public function testInvalid(array $addresses)
    {
        $this->assertNull(Factory::getRangeFromAddresses($addresses));
    }

    /**
     * @return array{array{mixed}, string, 2?: int}[]
     */
    public function validProvider()
    {
        return array(
            array(array('1.2.3.4'), '1.2.3.4'),
            array(array('1.2.3.4', Factory::parseAddressString('1.2.3.4')), '1.2.3.4'),
            array(array('0.0.0.0'), '0.0.0.0'),
            array(array('255.255.255.255'), '255.255.255.255'),
            array(array('0.0.0.0', '255.255.255.255'), '0.0.0.0/0'),
            array(array('0.0.0.0', '127.255.255.255'), '0.0.0.0/1'),
            array(array('128.0.0.0', '255.255.255.255'), '128.0.0.0/1'),
            array(array('255.255.255.255', '128.0.0.0', '128.0.0.0'), '128.0.0.0/1'),
            array(array(Factory::parseAddressString('::')), '::'),
            array(array(Factory::parseAddressString('::')), '::'),
            array(
                array(
                    '131.100.75.124',
                    '131.100.72.225',
                    '131.100.72.237',
                    '131.100.72.96',
                    '131.100.73.125',
                    '131.100.73.139',
                    '131.100.74.24',
                    '131.100.74.243:80',
                ),
                '131.100.72.0/22',
                ParseStringFlag::MAY_INCLUDE_PORT,
            ),
        );
    }

    /**
     * @dataProvider validProvider
     *
     * @param non-empty-array{non-empty-string|\IPLib\Address\AddressInterface} $addresses
     * @param non-empty-string $expectedSubnetStringRepresentation
     * @param int $flags
     *
     * @return void
     */
    public function testValid(array $addresses, $expectedSubnetStringRepresentation, $flags = 0)
    {
        $subnet = Factory::getRangeFromAddresses($addresses, $flags);
        $this->assertNotNull($subnet);
        $this->assertSame($expectedSubnetStringRepresentation, (string) $subnet);
    }
}
