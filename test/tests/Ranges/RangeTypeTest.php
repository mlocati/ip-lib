<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Type;
use IPLib\Test\TestCase;

class RangeTypeTest extends TestCase
{
    public function ipProvider()
    {
        return array(
            // 0.0.0.0/32
            array('0.0.0.0/32', Type::UNSPECIFIED),
            array('0.0.0.1/32', Type::THIS_NETWORK),
            // 0.0.0.0/8
            array('0.1.0.0/8', null),
            array('0.255.255.255/8', null),
            array('0.*.*.*', null),
            // 10.0.0.0/8
            array('10.0.0.0/8', Type::PRIVATE_NETWORK),
            array('10.1.0.0/8', Type::PRIVATE_NETWORK),
            array('10.255.255.255/8', Type::PRIVATE_NETWORK),
            array('10.*.*.*', Type::PRIVATE_NETWORK),
            // 127.0.0.0/8
            array('127.0.0.0/8', Type::LOOPBACK),
            array('127.0.0.1/8', Type::LOOPBACK),
            array('127.255.255.255/8', Type::LOOPBACK),
            array('127.*.*.*', Type::LOOPBACK),
            // 169.254.0.0/16
            array('169.254.0.0/16', Type::LINK_LOCAL),
            array('169.254.1.0/16', Type::LINK_LOCAL),
            array('169.254.255.255/16', Type::LINK_LOCAL),
            array('169.254.*.*', Type::LINK_LOCAL),
            // 172.16.0.0/12
            array('172.16.0.0/12', Type::PRIVATE_NETWORK),
            array('172.16.0.1/12', Type::PRIVATE_NETWORK),
            array('172.16.255.255/12', Type::PRIVATE_NETWORK),
            array('172.31.0.0/12', Type::PRIVATE_NETWORK),
            array('172.31.0.1/12', Type::PRIVATE_NETWORK),
            array('172.31.255.255/12', Type::PRIVATE_NETWORK),
            // 192.0.0.0/24
            array('192.0.0.0/24', Type::RESERVED),
            array('192.0.0.1/24', Type::RESERVED),
            array('192.0.0.255/24', Type::RESERVED),
            array('192.0.0.*', Type::RESERVED),
            // 192.0.2.0/24
            array('192.0.2.0/24', Type::RESERVED),
            array('192.0.2.1/24', Type::RESERVED),
            array('192.0.2.255/24', Type::RESERVED),
            array('192.0.2.*', Type::RESERVED),
            // 192.88.99.0/24
            array('192.88.99.0/24', Type::ANYCAST_RELAY),
            array('192.88.99.1/24', Type::ANYCAST_RELAY),
            array('192.88.99.255/24', Type::ANYCAST_RELAY),
            array('192.88.99.*', Type::ANYCAST_RELAY),
            // 192.168.0.0/16
            array('192.168.0.0/16', Type::PRIVATE_NETWORK),
            array('192.168.0.1/16', Type::PRIVATE_NETWORK),
            array('192.168.0.255/16', Type::PRIVATE_NETWORK),
            array('192.168.1.0/16', Type::PRIVATE_NETWORK),
            array('192.168.1.1/16', Type::PRIVATE_NETWORK),
            array('192.168.1.255/16', Type::PRIVATE_NETWORK),
            array('192.168.255.0/16', Type::PRIVATE_NETWORK),
            array('192.168.255.1/16', Type::PRIVATE_NETWORK),
            array('192.168.255.255/16', Type::PRIVATE_NETWORK),
            array('192.168.*.*', Type::PRIVATE_NETWORK),
            // 198.18.0.0/15
            array('198.18.0.0/15', Type::RESERVED),
            array('198.18.0.1/15', Type::RESERVED),
            array('198.18.0.255/15', Type::RESERVED),
            array('198.18.1.0/15', Type::RESERVED),
            array('198.18.1.1/15', Type::RESERVED),
            array('198.18.1.255/15', Type::RESERVED),
            array('198.18.255.0/15', Type::RESERVED),
            array('198.18.255.1/15', Type::RESERVED),
            array('198.18.255.255/15', Type::RESERVED),
            array('198.19.0.0/15', Type::RESERVED),
            array('198.19.0.1/15', Type::RESERVED),
            array('198.19.0.255/15', Type::RESERVED),
            array('198.19.1.0/15', Type::RESERVED),
            array('198.19.1.1/15', Type::RESERVED),
            array('198.19.1.255/15', Type::RESERVED),
            array('198.19.255.0/15', Type::RESERVED),
            array('198.19.255.1/15', Type::RESERVED),
            array('198.19.255.255/15', Type::RESERVED),
            // 198.51.100.0/24
            array('198.51.100.0/24', Type::RESERVED),
            array('198.51.100.1/24', Type::RESERVED),
            array('198.51.100.255/24', Type::RESERVED),
            array('198.51.100.*', Type::RESERVED),
            // 203.0.113.0/24
            array('203.0.113.0/24', Type::RESERVED),
            array('203.0.113.1/24', Type::RESERVED),
            array('203.0.113.255/24', Type::RESERVED),
            array('203.0.113.*', Type::RESERVED),
            // 224.0.0.0/4
            array('224.0.0.0/4', Type::MULTICAST),
            array('224.0.0.1/4', Type::MULTICAST),
            array('224.0.0.255/4', Type::MULTICAST),
            array('224.0.1.0/4', Type::MULTICAST),
            array('224.0.1.1/4', Type::MULTICAST),
            array('224.0.1.255/4', Type::MULTICAST),
            array('224.0.255.0/4', Type::MULTICAST),
            array('224.0.255.1/4', Type::MULTICAST),
            array('224.0.255.255/4', Type::MULTICAST),
            array('224.1.0.0/4', Type::MULTICAST),
            array('224.1.0.1/4', Type::MULTICAST),
            array('224.1.0.255/4', Type::MULTICAST),
            array('224.1.1.0/4', Type::MULTICAST),
            array('224.1.1.1/4', Type::MULTICAST),
            array('224.1.1.255/4', Type::MULTICAST),
            array('224.1.255.0/4', Type::MULTICAST),
            array('224.1.255.1/4', Type::MULTICAST),
            array('224.1.255.255/4', Type::MULTICAST),
            array('224.255.0.0/4', Type::MULTICAST),
            array('224.255.0.1/4', Type::MULTICAST),
            array('224.255.0.255/4', Type::MULTICAST),
            array('224.255.1.0/4', Type::MULTICAST),
            array('224.255.1.1/4', Type::MULTICAST),
            array('224.255.1.255/4', Type::MULTICAST),
            array('224.255.255.0/4', Type::MULTICAST),
            array('224.255.255.1/4', Type::MULTICAST),
            array('224.255.255.255/4', Type::MULTICAST),
            array('230.0.0.0/4', Type::MULTICAST),
            array('230.0.0.1/4', Type::MULTICAST),
            array('230.0.0.255/4', Type::MULTICAST),
            array('230.0.1.0/4', Type::MULTICAST),
            array('230.0.1.1/4', Type::MULTICAST),
            array('230.0.1.255/4', Type::MULTICAST),
            array('230.0.255.0/4', Type::MULTICAST),
            array('230.0.255.1/4', Type::MULTICAST),
            array('230.0.255.255/4', Type::MULTICAST),
            array('230.1.0.0/4', Type::MULTICAST),
            array('230.1.0.1/4', Type::MULTICAST),
            array('230.1.0.255/4', Type::MULTICAST),
            array('230.1.1.0/4', Type::MULTICAST),
            array('230.1.1.1/4', Type::MULTICAST),
            array('230.1.1.255/4', Type::MULTICAST),
            array('230.1.255.0/4', Type::MULTICAST),
            array('230.1.255.1/4', Type::MULTICAST),
            array('230.1.255.255/4', Type::MULTICAST),
            array('230.255.0.0/4', Type::MULTICAST),
            array('230.255.0.1/4', Type::MULTICAST),
            array('230.255.0.255/4', Type::MULTICAST),
            array('230.255.1.0/4', Type::MULTICAST),
            array('230.255.1.1/4', Type::MULTICAST),
            array('230.255.1.255/4', Type::MULTICAST),
            array('230.255.255.0/4', Type::MULTICAST),
            array('230.255.255.1/4', Type::MULTICAST),
            array('230.255.255.255/4', Type::MULTICAST),
            array('239.0.0.0/4', Type::MULTICAST),
            array('239.0.0.1/4', Type::MULTICAST),
            array('239.0.0.255/4', Type::MULTICAST),
            array('239.0.1.0/4', Type::MULTICAST),
            array('239.0.1.1/4', Type::MULTICAST),
            array('239.0.1.255/4', Type::MULTICAST),
            array('239.0.255.0/4', Type::MULTICAST),
            array('239.0.255.1/4', Type::MULTICAST),
            array('239.0.255.255/4', Type::MULTICAST),
            array('239.1.0.0/4', Type::MULTICAST),
            array('239.1.0.1/4', Type::MULTICAST),
            array('239.1.0.255/4', Type::MULTICAST),
            array('239.1.1.0/4', Type::MULTICAST),
            array('239.1.1.1/4', Type::MULTICAST),
            array('239.1.1.255/4', Type::MULTICAST),
            array('239.1.255.0/4', Type::MULTICAST),
            array('239.1.255.1/4', Type::MULTICAST),
            array('239.1.255.255/4', Type::MULTICAST),
            array('239.255.0.0/4', Type::MULTICAST),
            array('239.255.0.1/4', Type::MULTICAST),
            array('239.255.0.255/4', Type::MULTICAST),
            array('239.255.1.0/4', Type::MULTICAST),
            array('239.255.1.1/4', Type::MULTICAST),
            array('239.255.1.255/4', Type::MULTICAST),
            array('239.255.255.0/4', Type::MULTICAST),
            array('239.255.255.1/4', Type::MULTICAST),
            array('239.255.255.255/4', Type::MULTICAST),
            // 255.255.255.255/32
            array('255.255.255.255/32', Type::LIMITED_BROADCAST),
            // 240.0.0.0/4
            array('240.0.0.0/4', null),
            array('240.0.0.0/32', Type::RESERVED),
            array('240.0.0.1/4', null),
            array('240.0.0.1/31', Type::RESERVED),
            array('240.*.*.*', Type::RESERVED),
            array('247.127.127.127/4', null),
            array('247.127.127.127/32', Type::RESERVED),
            array('247.*.*.*', Type::RESERVED),
            array('248.128.128.128/28', Type::RESERVED),
            array('248.*.*.*', Type::RESERVED),
            array('255.255.255.0/28', Type::RESERVED),
            array('255.255.255.253/32', Type::RESERVED),
            array('255.255.255.254/28', null),
            array('255.*.*.*', null),
            // Public addresses
            array('2001:503:ba3e::2:30/32', Type::PUBLIC_NETWORK),
            array('216.58.212.68/32', Type::PUBLIC_NETWORK),
            array('31.11.33.139/32', Type::PUBLIC_NETWORK),
            array('104.25.25.33/32', Type::PUBLIC_NETWORK),
            // ::/128
            array('0000:0000:0000:0000:0000:0000:0000:0000/128', Type::UNSPECIFIED),
            // ::1/128
            array('0000:0000:0000:0000:0000:0000:0000:0001/128', Type::LOOPBACK),
            // ::/127
            array('0000:0000:0000:0000:0000:0000:0000:0000/127', null),
            // 100::/8
            array('0100:0000:0000:0000:0000:0000:0000:0000/8', null),
            array('0100:0000:0000:0000:0000:0000:0000:0000/64', Type::DISCARD_ONLY),
            array('01ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/128', Type::DISCARD),
            // 100::/64
            array('0000:0000:0000:0000:0000:0000:0000:0002/8', null),
            array('0000:0000:0000:0000:0000:0000:0000:ffff/8', null),
            array('0001:0000:0000:0000:0000:0000:0000:0002/8', null),
            array('00ff:0000:0000:0000:0000:0000:0000:0000/8', null),
            array('00ff:0000:0000:0000:0000:0000:0000:0000/8', null),
            array('00ff:0000:0000:0000:0000:0000:0000:ffff/8', null),
            array('00ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/8', null),
            // 00ff::/16
            array('00ff:0000:0000:0000:0000:0000:0000:ffff/16', Type::RESERVED),
            array('00ff:*:*:*:*:*:*:*', Type::RESERVED),
            // 0100::/64
            array('0100:0000:0000:0000:0000:0000:0000:0000/64', Type::DISCARD_ONLY),
            array('0100:0000:0000:0000:ffff:ffff:ffff:ffff/64', Type::DISCARD_ONLY),
            array('0100:0000:0000:0000:*:*:*:*', Type::DISCARD_ONLY),
            // 0100::/8
            array('0100:0000:0000:0001:0000:0000:0000:0000/8', null),
            array('01ff:0000:0000:0000:0000:0000:0000:0000/8', null),
            array('01ff:0000:0000:0000:0000:0000:0000:0000/9', Type::DISCARD),
            array('01ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/8', null),
            array('01ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/9', Type::DISCARD),
            array('0100:*:*:*:*:*:*:*', null),
            // 0200::/7
            array('0200:0000:0000:0000:0000:0000:0000:0000/7', Type::RESERVED),
            array('0200:0000:0000:0000:0000:0000:0000:0009/7', Type::RESERVED),
            array('0200:0000:0000:0000:0000:000f:0000:0009/7', Type::RESERVED),
            array('03ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/7', Type::RESERVED),
            // 0400::/6
            array('0400:0000:0000:0000:0000:0000:0000:0000/6', Type::RESERVED),
            array('07ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/6', Type::RESERVED),
            // 0800::/5
            array('0800:0000:0000:0000:0000:0000:0000:0000/5', Type::RESERVED),
            array('0fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/5', Type::RESERVED),
            // 1000::/4
            array('1000:0000:0000:0000:0000:0000:0000:0000/4', Type::RESERVED),
            array('1fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/4', Type::RESERVED),
            // 2002::/48
            array('2002:0000:0000:0000:0000:0000:0000:0000/48', Type::UNSPECIFIED), //Assumed as 0.0.0.0 IPv4
            array('2002:0000:0000:ffff:ffff:ffff:ffff:ffff/48', Type::UNSPECIFIED), //Assumed as 0.0.0.0 IPv4
            array('2002:0000:0000:*:*:*:*:*', Type::UNSPECIFIED), //Assumed as 0.0.0.0 IPv4
            array('2002:7f00:0001:0000:0000:0000:0000:0000/48', Type::LOOPBACK), //Assumed as 127.0.0.1 IPv4
            array('2002:7f00:0001:ffff:ffff:ffff:ffff:ffff/48', Type::LOOPBACK), //Assumed as 127.0.0.1 IPv4
            array('2002:7f00:0001:*:*:*:*:*', Type::LOOPBACK), //Assumed as 127.0.0.1 IPv4
            array('2002:ffff:ffff:0000:0000:0000:0000:0000/48', Type::LIMITED_BROADCAST), //Assumed as 255.255.255.255 IPv4
            array('2002:ffff:ffff:ffff:ffff:ffff:ffff:ffff/48', Type::LIMITED_BROADCAST), //Assumed as 255.255.255.255 IPv4
            array('2002:ffff:ffff:*:*:*:*:*', Type::LIMITED_BROADCAST), //Assumed as 255.255.255.255 IPv4
            // 2000::/3
            array('2000:0000:0000:0000:0000:0000:0000:0000/3', Type::PUBLIC_NETWORK),
            array('3fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::PUBLIC_NETWORK),
            // 4000::/3
            array('4000:0000:0000:0000:0000:0000:0000:0000/3', Type::RESERVED),
            array('5fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::RESERVED),
            // 6000::/3
            array('6000:0000:0000:0000:0000:0000:0000:0000/3', Type::RESERVED),
            array('7fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::RESERVED),
            // 8000::/3
            array('8000:0000:0000:0000:0000:0000:0000:0000/3', Type::RESERVED),
            array('9fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::RESERVED),
            // a000::/3
            array('a000:0000:0000:0000:0000:0000:0000:0000/3', Type::RESERVED),
            array('bfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::RESERVED),
            // c000::/3
            array('c000:0000:0000:0000:0000:0000:0000:0000/3', Type::RESERVED),
            array('dfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::RESERVED),
            // e000::/4
            array('e000:0000:0000:0000:0000:0000:0000:0000/4', Type::RESERVED),
            array('efff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/4', Type::RESERVED),
            // f000::/5
            array('f000:0000:0000:0000:0000:0000:0000:0000/5', Type::RESERVED),
            array('f7ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/5', Type::RESERVED),
            // f800::/6
            array('f800:0000:0000:0000:0000:0000:0000:0000/6', Type::RESERVED),
            array('fbff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/6', Type::RESERVED),
            // fc00::/7
            array('fc00:0000:0000:0000:0000:0000:0000:0000/7', Type::PRIVATE_NETWORK),
            array('fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/7', Type::PRIVATE_NETWORK),
            // fe00::/9
            array('fe00:0000:0000:0000:0000:0000:0000:0000/9', Type::RESERVED),
            array('fe7f:ffff:ffff:ffff:ffff:ffff:ffff:ffff/9', Type::RESERVED),
            // fe80::/10
            array('fe80:0000:0000:0000:0000:0000:0000:0000/10', Type::LINK_LOCAL_UNICAST),
            array('febf:ffff:ffff:ffff:ffff:ffff:ffff:ffff/10', Type::LINK_LOCAL_UNICAST),
            // fec0::/10
            array('fec0:0000:0000:0000:0000:0000:0000:0000/10', Type::RESERVED),
            array('feff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/10', Type::RESERVED),
            // ff00::/8
            array('ff00:0000:0000:0000:0000:0000:0000:0000/8', Type::MULTICAST),
            array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/8', Type::MULTICAST),
        );
    }

    /**
     * @dataProvider ipProvider
     *
     * @param string $rangeString
     * @param int $expectedType
     */
    public function testRangeTypes($rangeString, $expectedType)
    {
        $range = Factory::rangeFromString($rangeString);
        $this->assertNotNull($range, "'{$rangeString}' has been detected as an invalid subnet, but it should be valid");
        $detectedType = $range->getRangeType();
        $this->assertSame($expectedType, $detectedType, sprintf("'%s' has been detected as\n%s\ninstead of\n%s", $range->toString(), Type::getName($detectedType), Type::getName($expectedType)));
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
