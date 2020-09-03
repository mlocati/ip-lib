<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Range\Type;
use IPLib\Test\TestCase;

class RangeTypeTest extends TestCase
{
    public function ipProvider()
    {
        return array(
            // 0.0.0.0/32
            array('0.0.0.0', Type::UNSPECIFIED),
            // 0.0.0.0/8
            array('0.1.0.0', Type::THIS_NETWORK),
            array('0.255.255.255', Type::THIS_NETWORK),
            // 10.0.0.0/8
            array('10.0.0.0', Type::PRIVATE_NETWORK),
            array('10.1.0.0', Type::PRIVATE_NETWORK),
            array('10.255.255.255', Type::PRIVATE_NETWORK),
            // 100.64.0.0/10
            array('100.64.0.0', Type::CARRIER_GRADE_NAT),
            array('100.64.0.1', Type::CARRIER_GRADE_NAT),
            array('100.127.255.254', Type::CARRIER_GRADE_NAT),
            array('100.127.255.255', Type::CARRIER_GRADE_NAT),
            // 127.0.0.0/8
            array('127.0.0.0', Type::LOOPBACK),
            array('127.0.0.1', Type::LOOPBACK),
            array('127.255.255.255', Type::LOOPBACK),
            // 169.254.0.0/16
            array('169.254.0.0', Type::LINK_LOCAL),
            array('169.254.1.0', Type::LINK_LOCAL),
            array('169.254.255.255', Type::LINK_LOCAL),
            // 172.16.0.0/12
            array('172.16.0.0', Type::PRIVATE_NETWORK),
            array('172.16.0.1', Type::PRIVATE_NETWORK),
            array('172.16.255.255', Type::PRIVATE_NETWORK),
            array('172.31.0.0', Type::PRIVATE_NETWORK),
            array('172.31.0.1', Type::PRIVATE_NETWORK),
            array('172.31.255.255', Type::PRIVATE_NETWORK),
            // 192.0.0.0/24
            array('192.0.0.0', Type::RESERVED),
            array('192.0.0.1', Type::RESERVED),
            array('192.0.0.255', Type::RESERVED),
            // 192.0.2.0/24
            array('192.0.2.0', Type::RESERVED),
            array('192.0.2.1', Type::RESERVED),
            array('192.0.2.255', Type::RESERVED),
            // 192.88.99.0/24
            array('192.88.99.0', Type::ANYCAST_RELAY),
            array('192.88.99.1', Type::ANYCAST_RELAY),
            array('192.88.99.255', Type::ANYCAST_RELAY),
            // 192.168.0.0/16
            array('192.168.0.0', Type::PRIVATE_NETWORK),
            array('192.168.0.1', Type::PRIVATE_NETWORK),
            array('192.168.0.255', Type::PRIVATE_NETWORK),
            array('192.168.1.0', Type::PRIVATE_NETWORK),
            array('192.168.1.1', Type::PRIVATE_NETWORK),
            array('192.168.1.255', Type::PRIVATE_NETWORK),
            array('192.168.255.0', Type::PRIVATE_NETWORK),
            array('192.168.255.1', Type::PRIVATE_NETWORK),
            array('192.168.255.255', Type::PRIVATE_NETWORK),
            // 198.18.0.0/15
            array('198.18.0.0', Type::RESERVED),
            array('198.18.0.1', Type::RESERVED),
            array('198.18.0.255', Type::RESERVED),
            array('198.18.1.0', Type::RESERVED),
            array('198.18.1.1', Type::RESERVED),
            array('198.18.1.255', Type::RESERVED),
            array('198.18.255.0', Type::RESERVED),
            array('198.18.255.1', Type::RESERVED),
            array('198.18.255.255', Type::RESERVED),
            array('198.19.0.0', Type::RESERVED),
            array('198.19.0.1', Type::RESERVED),
            array('198.19.0.255', Type::RESERVED),
            array('198.19.1.0', Type::RESERVED),
            array('198.19.1.1', Type::RESERVED),
            array('198.19.1.255', Type::RESERVED),
            array('198.19.255.0', Type::RESERVED),
            array('198.19.255.1', Type::RESERVED),
            array('198.19.255.255', Type::RESERVED),
            // 198.51.100.0/24
            array('198.51.100.0', Type::RESERVED),
            array('198.51.100.1', Type::RESERVED),
            array('198.51.100.255', Type::RESERVED),
            // 203.0.113.0/24
            array('203.0.113.0', Type::RESERVED),
            array('203.0.113.1', Type::RESERVED),
            array('203.0.113.255', Type::RESERVED),
            // 224.0.0.0/4
            array('224.0.0.0', Type::MULTICAST),
            array('224.0.0.1', Type::MULTICAST),
            array('224.0.0.255', Type::MULTICAST),
            array('224.0.1.0', Type::MULTICAST),
            array('224.0.1.1', Type::MULTICAST),
            array('224.0.1.255', Type::MULTICAST),
            array('224.0.255.0', Type::MULTICAST),
            array('224.0.255.1', Type::MULTICAST),
            array('224.0.255.255', Type::MULTICAST),
            array('224.1.0.0', Type::MULTICAST),
            array('224.1.0.1', Type::MULTICAST),
            array('224.1.0.255', Type::MULTICAST),
            array('224.1.1.0', Type::MULTICAST),
            array('224.1.1.1', Type::MULTICAST),
            array('224.1.1.255', Type::MULTICAST),
            array('224.1.255.0', Type::MULTICAST),
            array('224.1.255.1', Type::MULTICAST),
            array('224.1.255.255', Type::MULTICAST),
            array('224.255.0.0', Type::MULTICAST),
            array('224.255.0.1', Type::MULTICAST),
            array('224.255.0.255', Type::MULTICAST),
            array('224.255.1.0', Type::MULTICAST),
            array('224.255.1.1', Type::MULTICAST),
            array('224.255.1.255', Type::MULTICAST),
            array('224.255.255.0', Type::MULTICAST),
            array('224.255.255.1', Type::MULTICAST),
            array('224.255.255.255', Type::MULTICAST),
            array('230.0.0.0', Type::MULTICAST),
            array('230.0.0.1', Type::MULTICAST),
            array('230.0.0.255', Type::MULTICAST),
            array('230.0.1.0', Type::MULTICAST),
            array('230.0.1.1', Type::MULTICAST),
            array('230.0.1.255', Type::MULTICAST),
            array('230.0.255.0', Type::MULTICAST),
            array('230.0.255.1', Type::MULTICAST),
            array('230.0.255.255', Type::MULTICAST),
            array('230.1.0.0', Type::MULTICAST),
            array('230.1.0.1', Type::MULTICAST),
            array('230.1.0.255', Type::MULTICAST),
            array('230.1.1.0', Type::MULTICAST),
            array('230.1.1.1', Type::MULTICAST),
            array('230.1.1.255', Type::MULTICAST),
            array('230.1.255.0', Type::MULTICAST),
            array('230.1.255.1', Type::MULTICAST),
            array('230.1.255.255', Type::MULTICAST),
            array('230.255.0.0', Type::MULTICAST),
            array('230.255.0.1', Type::MULTICAST),
            array('230.255.0.255', Type::MULTICAST),
            array('230.255.1.0', Type::MULTICAST),
            array('230.255.1.1', Type::MULTICAST),
            array('230.255.1.255', Type::MULTICAST),
            array('230.255.255.0', Type::MULTICAST),
            array('230.255.255.1', Type::MULTICAST),
            array('230.255.255.255', Type::MULTICAST),
            array('239.0.0.0', Type::MULTICAST),
            array('239.0.0.1', Type::MULTICAST),
            array('239.0.0.255', Type::MULTICAST),
            array('239.0.1.0', Type::MULTICAST),
            array('239.0.1.1', Type::MULTICAST),
            array('239.0.1.255', Type::MULTICAST),
            array('239.0.255.0', Type::MULTICAST),
            array('239.0.255.1', Type::MULTICAST),
            array('239.0.255.255', Type::MULTICAST),
            array('239.1.0.0', Type::MULTICAST),
            array('239.1.0.1', Type::MULTICAST),
            array('239.1.0.255', Type::MULTICAST),
            array('239.1.1.0', Type::MULTICAST),
            array('239.1.1.1', Type::MULTICAST),
            array('239.1.1.255', Type::MULTICAST),
            array('239.1.255.0', Type::MULTICAST),
            array('239.1.255.1', Type::MULTICAST),
            array('239.1.255.255', Type::MULTICAST),
            array('239.255.0.0', Type::MULTICAST),
            array('239.255.0.1', Type::MULTICAST),
            array('239.255.0.255', Type::MULTICAST),
            array('239.255.1.0', Type::MULTICAST),
            array('239.255.1.1', Type::MULTICAST),
            array('239.255.1.255', Type::MULTICAST),
            array('239.255.255.0', Type::MULTICAST),
            array('239.255.255.1', Type::MULTICAST),
            array('239.255.255.255', Type::MULTICAST),
            // 255.255.255.255/32
            array('255.255.255.255', Type::LIMITED_BROADCAST),
            // 240.0.0.0/4
            array('240.0.0.0', Type::RESERVED),
            array('240.0.0.1', Type::RESERVED),
            array('247.127.127.127', Type::RESERVED),
            array('248.128.128.128', Type::RESERVED),
            array('255.255.255.0', Type::RESERVED),
            array('255.255.255.253', Type::RESERVED),
            array('255.255.255.254', Type::RESERVED),
            // Public addresses
            array('2001:503:ba3e::2:30', Type::PUBLIC_NETWORK),
            array('216.58.212.68', Type::PUBLIC_NETWORK),
            array('31.11.33.139', Type::PUBLIC_NETWORK),
            array('104.25.25.33', Type::PUBLIC_NETWORK),
            // ::/128
            array('0000:0000:0000:0000:0000:0000:0000:0000', Type::UNSPECIFIED),
            // ::1/128
            array('0000:0000:0000:0000:0000:0000:0000:0001', Type::LOOPBACK),
            // 0000::/8
            array('0000:0000:0000:0000:0000:0000:0000:0002', Type::RESERVED),
            array('0000:0000:0000:0000:0000:0000:0000:ffff', Type::RESERVED),
            array('0001:0000:0000:0000:0000:0000:0000:0002', Type::RESERVED),
            array('00ff:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('00ff:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('00ff:0000:0000:0000:0000:0000:0000:ffff', Type::RESERVED),
            array('00ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // 0100::/64
            array('0100:0000:0000:0000:0000:0000:0000:0000', Type::DISCARD_ONLY),
            array('0100:0000:0000:0000:ffff:ffff:ffff:ffff', Type::DISCARD_ONLY),
            // 0100::/8
            array('0100:0000:0000:0001:0000:0000:0000:0000', Type::DISCARD),
            array('01ff:0000:0000:0000:0000:0000:0000:0000', Type::DISCARD),
            array('01ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::DISCARD),
            // 0200::/7
            array('0200:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('0200:0000:0000:0000:0000:0000:0000:0009', Type::RESERVED),
            array('0200:0000:0000:0000:0000:000f:0000:0009', Type::RESERVED),
            array('03ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // 0400::/6
            array('0400:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('07ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // 0800::/5
            array('0800:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('0fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // 1000::/4
            array('1000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('1fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // 2002::/16
            array('2002:0000:0000:0000:0000:0000:0000:0000', Type::UNSPECIFIED), //Assumed as 0.0.0.0 IPv4
            array('2002:0000:0000:ffff:ffff:ffff:ffff:ffff', Type::UNSPECIFIED), //Assumed as 0.0.0.0 IPv4
            array('2002:7f00:0001:0000:0000:0000:0000:0000', Type::LOOPBACK), //Assumed as 127.0.0.1 IPv4
            array('2002:7f00:0001:ffff:ffff:ffff:ffff:ffff', Type::LOOPBACK), //Assumed as 127.0.0.1 IPv4
            array('2002:ffff:ffff:0000:0000:0000:0000:0000', Type::LIMITED_BROADCAST), //Assumed as 255.255.255.255 IPv4
            array('2002:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::LIMITED_BROADCAST), //Assumed as 255.255.255.255 IPv4
            // 2000::/3
            array('2000:0000:0000:0000:0000:0000:0000:0000', Type::PUBLIC_NETWORK),
            array('3fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::PUBLIC_NETWORK),
            // 4000::/3
            array('4000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('5fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // 6000::/3
            array('6000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('7fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // 8000::/3
            array('8000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('9fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // a000::/3
            array('a000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('bfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // c000::/3
            array('c000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('dfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // e000::/4
            array('e000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('efff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // f000::/5
            array('f000:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('f7ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // f800::/6
            array('f800:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('fbff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // fc00::/7
            array('fc00:0000:0000:0000:0000:0000:0000:0000', Type::PRIVATE_NETWORK),
            array('fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::PRIVATE_NETWORK),
            // fe00::/9
            array('fe00:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('fe7f:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // fe80::/10
            array('fe80:0000:0000:0000:0000:0000:0000:0000', Type::LINK_LOCAL_UNICAST),
            array('febf:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::LINK_LOCAL_UNICAST),
            // fec0::/10
            array('fec0:0000:0000:0000:0000:0000:0000:0000', Type::RESERVED),
            array('feff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::RESERVED),
            // ff00::/8
            array('ff00:0000:0000:0000:0000:0000:0000:0000', Type::MULTICAST),
            array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::MULTICAST),
        );
    }

    /**
     * @dataProvider ipProvider
     *
     * @param string $address
     * @param int $expectedType
     */
    public function testRangeTypes($address, $expectedType)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNotNull($ip, "'{$address}' has been detected as an invalid IP, but it should be valid");
        $detectedType = $ip->getRangeType();
        $this->assertSame($expectedType, $detectedType, sprintf("'%s' has been detected as\n%s\ninstead of\n%s", $ip->toString(), Type::getName($detectedType), Type::getName($expectedType)));
    }

    public function rangeTypeNameProvider()
    {
        return array(
            array(null, 'Unknown type'),
            array('x', 'Unknown type (x)'),
            array(-1, 'Unknown type (-1)'),
        );
    }

    /**
     * @dataProvider rangeTypeNameProvider
     *
     * @param int|mixed $type
     * @param string $expectedName
     */
    public function testRangeTypeName($type, $expectedName)
    {
        $this->assertSame($expectedName, Type::getName($type));
    }
}
