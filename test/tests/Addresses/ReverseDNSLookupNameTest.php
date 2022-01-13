<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\ParseStringFlag;
use IPLib\Test\TestCase;

class ReverseDNSLookupNameTest extends TestCase
{
    public function reverseDNSAddressProvider()
    {
        return array(
            array('0.0.0.0', '0.0.0.0.in-addr.arpa'),
            array('0.0.0.1', '1.0.0.0.in-addr.arpa'),
            array('1.2.3.4', '4.3.2.1.in-addr.arpa'),
            array('255.128.127.0', '0.127.128.255.in-addr.arpa'),
            array('::', '0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa'),
            array('::1', '1.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa'),
            array('::ffff', 'f.f.f.f.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa'),
            array('1234::abcd', 'd.c.b.a.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.4.3.2.1.ip6.arpa'),
            array('0000:0000:0000:0000:0000:ffff:1234:fedc', 'c.d.e.f.4.3.2.1.f.f.f.f.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa'),
        );
    }

    /**
     * @dataProvider reverseDNSAddressProvider
     *
     * @param string $addressString
     * @param string $expectedReverseDNSAddress
     */
    public function testReverseDNSLookupName($addressString, $expectedReverseDNSAddress)
    {
        $address = Factory::addressFromString($addressString);
        $actualReverseDNSAddress = $address->getReverseDNSLookupName();
        $this->assertSame($expectedReverseDNSAddress, $actualReverseDNSAddress);
        $address2 = Factory::parseAddressString($actualReverseDNSAddress, ParseStringFlag::ADDRESS_MAYBE_RDNS);
        $this->assertNotNull($address2);
        $this->assertSame((string) $address, (string) $address2);
    }
}
