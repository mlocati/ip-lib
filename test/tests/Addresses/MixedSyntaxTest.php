<?php

namespace IPLib\Test\Addresses;

use IPLib\Address\IPv6;
use IPLib\Factory;
use IPLib\Test\TestCase;

/**
 * @see https://tools.ietf.org/html/rfc4291#section-2.2 point 3.
 */
class MixedSyntaxTest extends TestCase
{
    public function validMixedSyntaxProvider()
    {
        return array(
            array(
                '1:2:3:4:5::6:1.2.3.4',
                '1:2:3:4:5:6:102:304',
                '1:2:3:4:5:6:1.2.3.4',
                '0001:0002:0003:0004:0005:0006:1.2.3.4',
                '1:2:3:4:5:6:001.002.003.004',
                '0001:0002:0003:0004:0005:0006:001.002.003.004',
            ),
            array(
                '0:0:0:0:0:0:13.1.68.3',
                '::d01:4403',
                '::13.1.68.3',
                '0000:0000:0000:0000:0000:0000:13.1.68.3',
                '::013.001.068.003',
                '0000:0000:0000:0000:0000:0000:013.001.068.003',
            ),
            array(
                '::13.1.68.3',
                '::d01:4403',
                '::13.1.68.3',
                '0000:0000:0000:0000:0000:0000:13.1.68.3',
                '::013.001.068.003',
                '0000:0000:0000:0000:0000:0000:013.001.068.003',
            ),
            array(
                '0:0:0:0:0:FFFF:222.1.41.90',
                '::ffff:222.1.41.90',  // IPv4MappedIPv6
                '::ffff:222.1.41.90',
                '0000:0000:0000:0000:0000:ffff:222.1.41.90',
                '::ffff:222.001.041.090',
                '0000:0000:0000:0000:0000:ffff:222.001.041.090',
            ),
            array(
                '::ffff:127.000.000.001',
                '::ffff:127.0.0.1', // IPv4MappedIPv6
                '::ffff:127.0.0.1',
                '0000:0000:0000:0000:0000:ffff:127.0.0.1',
                '::ffff:127.000.000.001',
                '0000:0000:0000:0000:0000:ffff:127.000.000.001',
            ),
            array(
                '0:0:0:0:0:FFFF:129.144.52.38',
                '::ffff:129.144.52.38', // IPv4MappedIPv6
                '::ffff:129.144.52.38',
                '0000:0000:0000:0000:0000:ffff:129.144.52.38',
                '::ffff:129.144.052.038',
                '0000:0000:0000:0000:0000:ffff:129.144.052.038',
            ),
        );
    }

    /**
     * @dataProvider validMixedSyntaxProvider
     *
     * @param string $mixedRepresentation
     * @param string $expectedShortIPv6Representation
     * @param string $normalizedMixedRepresentationSS
     * @param string $normalizedMixedRepresentationLS
     * @param string $normalizedMixedRepresentationSL
     * @param string $normalizedMixedRepresentationLL
     */
    public function testValidMixedSyntax($mixedRepresentation, $expectedShortIPv6Representation, $normalizedMixedRepresentationSS, $normalizedMixedRepresentationLS, $normalizedMixedRepresentationSL, $normalizedMixedRepresentationLL)
    {
        /* @var $ip IPv6 */
        $ip = Factory::addressFromString($mixedRepresentation);
        $this->assertNotNull($ip, "Unable to parse the IPv6+IPv4 mixed syntax '{$mixedRepresentation}'");
        $calculatedShortSyntax = $ip->toString(false);
        $this->assertSame($expectedShortIPv6Representation, $calculatedShortSyntax, 'The default short IPv6 representation is wrong');
        $this->assertSame($expectedShortIPv6Representation, Factory::addressFromString($calculatedShortSyntax)->toString(false), 'Re-parsing the representation failed');
        $this->assertSame($normalizedMixedRepresentationSS, $ip->toMixedIPv6IPv4String(), 'The mixed IPv6+IPv4 representation is wrong (IPv6 short, IPv4 short)');
        $this->assertSame($normalizedMixedRepresentationLS, $ip->toMixedIPv6IPv4String(true), 'The mixed IPv6+IPv4 representation is wrong (IPv6 long, IPv4 short)');
        $this->assertSame($normalizedMixedRepresentationSL, $ip->toMixedIPv6IPv4String(false, true), 'The mixed IPv6+IPv4 representation is wrong (IPv6 short, IPv4 long)');
        $this->assertSame($normalizedMixedRepresentationLL, $ip->toMixedIPv6IPv4String(true, true), 'The mixed IPv6+IPv4 representation is wrong (IPv6 long, IPv4 long)');
    }

    public function invalidMixedSyntaxProvider()
    {
        return array(
            array(':13.1.68.3'),
            array('::13.1.68'),
            array('1:2:3:4:5:1.2.3.4'),
            array('1:2:3:4:5::6:7:1.2.3.4'),
        );
    }

    /**
     * @dataProvider invalidMixedSyntaxProvider
     *
     * @param string $mixedRepresentation
     */
    public function testInalidMixedSyntax($mixedRepresentation)
    {
        $ip = Factory::addressFromString($mixedRepresentation);
        $this->assertNull($ip, "The mixed IPv6+IPv4 '{$mixedRepresentation}' is wrong and should not be parsed correctly");
    }
}
