<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;

class ValidTest extends \PHPUnit_Framework_TestCase
{
    public function validAddressesProvider()
    {
        return array(
            array('::', '::', '0000:0000:0000:0000:0000:0000:0000:0000'),
            array('::1', '::1', '0000:0000:0000:0000:0000:0000:0000:0001'),
            array('1::', '1::', '0001:0000:0000:0000:0000:0000:0000:0000'),
            array('0::1', '::1', '0000:0000:0000:0000:0000:0000:0000:0001'),
            array('000::1', '::1', '0000:0000:0000:0000:0000:0000:0000:0001'),
            array('f::1', 'f::1', '000f:0000:0000:0000:0000:0000:0000:0001'),
            array('::f:0:1', '::f:0:1', '0000:0000:0000:0000:0000:000f:0000:0001'),
            array('1:02:003:0004::', '1:2:3:4::', '0001:0002:0003:0004:0000:0000:0000:0000'),
            array('::ffff:ffff:ffff:ffff:ffff:ffff:ffff', '0:ffff:ffff:ffff:ffff:ffff:ffff:ffff', '0000:ffff:ffff:ffff:ffff:ffff:ffff:ffff'),
        );
    }

    /**
     * @dataProvider validAddressesProvider
     */
    public function testValidAddresses($address, $short, $long)
    {
        $str = @strval($address);
        $ip = Factory::addressFromString($address);
        $this->assertNotNull($ip, "'$str' has been detected as an invalid IP, but it should be valid");
        $this->assertSame($short, $ip->toString(false));
        $this->assertSame($short, $ip->__toString());
        $this->assertSame($long, $ip->toString(true));
    }
}
