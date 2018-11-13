<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class IPv4MappedIPv6Test extends TestCase
{
    public function mappedAddressProvider()
    {
        return array(
            array('0:0:0:0:0:FFFF:222.1.41.90', '::ffff:222.1.41.90'),
            array('::ffff:127.000.000.001', '::ffff:127.0.0.1'),
        );
    }

    /**
     * @dataProvider mappedAddressProvider
     */
    public function testMappedAddress($ipv6, $expectedShortSyntax)
    {
        $ip = Factory::addressFromString($ipv6);
        $this->assertNotNull($ip, "Unable to parse IPv4 address mapped to IPv6 ($ipv6)");
        $calculatedShortSyntax = $ip->toString(false);
        $this->assertSame($expectedShortSyntax, $calculatedShortSyntax);
        $this->assertSame($expectedShortSyntax, Factory::addressFromString($calculatedShortSyntax)->toString(false));
    }
}
