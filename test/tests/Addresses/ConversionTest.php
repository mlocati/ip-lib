<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class ConversionTest extends TestCase
{
    public function validAddressesProvider()
    {
        return array(
            array('0.0.0.0'),
            array('127.0.0.1'),
            array('10.20.30.40'),
            array('255.255.255.255'),
        );
    }

    /**
     * @dataProvider validAddressesProvider
     */
    public function testV4toV6toV4($address)
    {
        $str = @strval($address);
        $ipV4 = Factory::addressFromString($address);
        $this->assertNotNull($ipV4, "'$str' has been detected as an invalid IP, but it should be valid");
        $this->assertInstanceOf('IPLib\Address\IPv4', $ipV4);
        /* @var \IPLib\Address\IPv4 $ipV4 */
        $ipV6 = $ipV4->toIPv6();
        $this->assertNotNull($ipV6, "'$str' couldn't be converted to IPv6");
        $ipV4back = $ipV6->toIPv4();
        $this->assertNotNull($ipV4, "'$str' has been converted to '".$ipV6->toString()."', but it coulnd't be converted back to IPv4");
        $this->assertSame($address, $ipV4back->toString());
    }
}
