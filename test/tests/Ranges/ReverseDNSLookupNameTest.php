<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

class ReverseDNSLookupNameTest extends TestCase
{
    /**
     * @return array{string, string[]}[]
     */
    public function reverseDNSAddressProvider()
    {
        return array(
            // IPv4
            array(
                '10.155.16.0/0',
                array(
                    'in-addr.arpa',
                ),
            ),
            array(
                '10.155.16.0/32',
                array(
                    '0.16.155.10.in-addr.arpa',
                ),
            ),
            array(
                '10.155.16.0/24',
                array(
                    '16.155.10.in-addr.arpa',
                ),
            ),
            array(
                '10.155.16.0/23',
                array(
                    '16.155.10.in-addr.arpa',
                    '17.155.10.in-addr.arpa',
                ),
            ),
            array(
                '10.155.16.0/22',
                array(
                    '16.155.10.in-addr.arpa',
                    '17.155.10.in-addr.arpa',
                    '18.155.10.in-addr.arpa',
                    '19.155.10.in-addr.arpa',
                ),
            ),
            array(
                '10.155.16.0',
                array(
                    '0.16.155.10.in-addr.arpa',
                ),
            ),
            array(
                '10.155.16.*',
                array(
                    '16.155.10.in-addr.arpa',
                ),
            ),
            array(
                '10.155.*.*',
                array(
                    '155.10.in-addr.arpa',
                ),
            ),
            array(
                '10.*.*.*',
                array(
                    '10.in-addr.arpa',
                ),
            ),
            array(
                '*.*.*.*',
                array(
                    'in-addr.arpa',
                ),
            ),
            // IPv6
            array(
                '2001:db8::/0',
                array(
                    'ip6.arpa',
                ),
            ),
            array(
                '2001:db8::/24',
                array(
                    'd.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::/29',
                array(
                    '8.b.d.0.1.0.0.2.ip6.arpa',
                    '9.b.d.0.1.0.0.2.ip6.arpa',
                    'a.b.d.0.1.0.0.2.ip6.arpa',
                    'b.b.d.0.1.0.0.2.ip6.arpa',
                    'c.b.d.0.1.0.0.2.ip6.arpa',
                    'd.b.d.0.1.0.0.2.ip6.arpa',
                    'e.b.d.0.1.0.0.2.ip6.arpa',
                    'f.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::/30',
                array(
                    '8.b.d.0.1.0.0.2.ip6.arpa',
                    '9.b.d.0.1.0.0.2.ip6.arpa',
                    'a.b.d.0.1.0.0.2.ip6.arpa',
                    'b.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::/31',
                array(
                    '8.b.d.0.1.0.0.2.ip6.arpa',
                    '9.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::/32',
                array(
                    '8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::/128',
                array(
                    '0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::',
                array(
                    '0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::',
                array(
                    '0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::*',
                array(
                    '0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::*:*',
                array(
                    '0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::*:*:*',
                array(
                    '0.0.0.0.0.0.0.0.0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::*:*:*:*',
                array(
                    '0.0.0.0.0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8::*:*:*:*:*',
                array(
                    '0.0.0.0.8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:db8:*:*:*:*:*:*',
                array(
                    '8.b.d.0.1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '2001:*:*:*:*:*:*:*',
                array(
                    '1.0.0.2.ip6.arpa',
                ),
            ),
            array(
                '*:*:*:*:*:*:*:*',
                array(
                    'ip6.arpa',
                ),
            ),
        );
    }

    /**
     * @dataProvider reverseDNSAddressProvider
     *
     * @param string $addressString
     * @param string[] $expectedReverseDNSAddresses
     *
     * @return void
     */
    public function testReverseDNSLookupName($addressString, array $expectedReverseDNSAddresses)
    {
        $range = Factory::rangeFromString($addressString);
        $this->assertInstanceOf('IPLib\Range\RangeInterface', $range);
        $actualReverseDNSAddress = $range->getReverseDNSLookupName();
        $this->assertSame($expectedReverseDNSAddresses, $actualReverseDNSAddress);
    }
}
