<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Range\Type;
use PHPUnit_Framework_TestCase;

class TypeTest extends PHPUnit_Framework_TestCase
{
    public function ipProvider()
    {
        return array(
            // 0.0.0.0/32
            array('0.0.0.0/32', Type::T_UNSPECIFIED),
            // 0.0.0.0/8
            array('0.1.0.0/8', Type::T_THISNETWORK),
            array('0.255.255.255/8', Type::T_THISNETWORK),
            // 10.0.0.0/8
            array('10.0.0.0/8', Type::T_PRIVATENETWORK),
            array('10.1.0.0/8', Type::T_PRIVATENETWORK),
            array('10.255.255.255/8', Type::T_PRIVATENETWORK),
            // 127.0.0.0/8
            array('127.0.0.0/8', Type::T_LOOPBACK),
            array('127.0.0.1/8', Type::T_LOOPBACK),
            array('127.255.255.255/8', Type::T_LOOPBACK),
            // 169.254.0.0/16
            array('169.254.0.0/16', Type::T_LINKLOCAL),
            array('169.254.1.0/16', Type::T_LINKLOCAL),
            array('169.254.255.255/16', Type::T_LINKLOCAL),
            // 172.16.0.0/12
            array('172.16.0.0/12', Type::T_PRIVATENETWORK),
            array('172.16.0.1/12', Type::T_PRIVATENETWORK),
            array('172.16.255.255/12', Type::T_PRIVATENETWORK),
            array('172.31.0.0/12', Type::T_PRIVATENETWORK),
            array('172.31.0.1/12', Type::T_PRIVATENETWORK),
            array('172.31.255.255/12', Type::T_PRIVATENETWORK),
            // 192.0.0.0/24
            array('192.0.0.0/24', Type::T_RESERVED),
            array('192.0.0.1/24', Type::T_RESERVED),
            array('192.0.0.255/24', Type::T_RESERVED),
            // 192.0.2.0/24
            array('192.0.2.0/24', Type::T_RESERVED),
            array('192.0.2.1/24', Type::T_RESERVED),
            array('192.0.2.255/24', Type::T_RESERVED),
            // 192.88.99.0/24
            array('192.88.99.0/24', Type::T_ANYCASTRELAY),
            array('192.88.99.1/24', Type::T_ANYCASTRELAY),
            array('192.88.99.255/24', Type::T_ANYCASTRELAY),
            // 192.168.0.0/16
            array('192.168.0.0/16', Type::T_PRIVATENETWORK),
            array('192.168.0.1/16', Type::T_PRIVATENETWORK),
            array('192.168.0.255/16', Type::T_PRIVATENETWORK),
            array('192.168.1.0/16', Type::T_PRIVATENETWORK),
            array('192.168.1.1/16', Type::T_PRIVATENETWORK),
            array('192.168.1.255/16', Type::T_PRIVATENETWORK),
            array('192.168.255.0/16', Type::T_PRIVATENETWORK),
            array('192.168.255.1/16', Type::T_PRIVATENETWORK),
            array('192.168.255.255/16', Type::T_PRIVATENETWORK),
            // 198.18.0.0/15
            array('198.18.0.0/15', Type::T_RESERVED),
            array('198.18.0.1/15', Type::T_RESERVED),
            array('198.18.0.255/15', Type::T_RESERVED),
            array('198.18.1.0/15', Type::T_RESERVED),
            array('198.18.1.1/15', Type::T_RESERVED),
            array('198.18.1.255/15', Type::T_RESERVED),
            array('198.18.255.0/15', Type::T_RESERVED),
            array('198.18.255.1/15', Type::T_RESERVED),
            array('198.18.255.255/15', Type::T_RESERVED),
            array('198.19.0.0/15', Type::T_RESERVED),
            array('198.19.0.1/15', Type::T_RESERVED),
            array('198.19.0.255/15', Type::T_RESERVED),
            array('198.19.1.0/15', Type::T_RESERVED),
            array('198.19.1.1/15', Type::T_RESERVED),
            array('198.19.1.255/15', Type::T_RESERVED),
            array('198.19.255.0/15', Type::T_RESERVED),
            array('198.19.255.1/15', Type::T_RESERVED),
            array('198.19.255.255/15', Type::T_RESERVED),
            // 198.51.100.0/24
            array('198.51.100.0/24', Type::T_RESERVED),
            array('198.51.100.1/24', Type::T_RESERVED),
            array('198.51.100.255/24', Type::T_RESERVED),
            // 203.0.113.0/24
            array('203.0.113.0/24', Type::T_RESERVED),
            array('203.0.113.1/24', Type::T_RESERVED),
            array('203.0.113.255/24', Type::T_RESERVED),
            // 224.0.0.0/4
            array('224.0.0.0/4', Type::T_MULTICAST),
            array('224.0.0.1/4', Type::T_MULTICAST),
            array('224.0.0.255/4', Type::T_MULTICAST),
            array('224.0.1.0/4', Type::T_MULTICAST),
            array('224.0.1.1/4', Type::T_MULTICAST),
            array('224.0.1.255/4', Type::T_MULTICAST),
            array('224.0.255.0/4', Type::T_MULTICAST),
            array('224.0.255.1/4', Type::T_MULTICAST),
            array('224.0.255.255/4', Type::T_MULTICAST),
            array('224.1.0.0/4', Type::T_MULTICAST),
            array('224.1.0.1/4', Type::T_MULTICAST),
            array('224.1.0.255/4', Type::T_MULTICAST),
            array('224.1.1.0/4', Type::T_MULTICAST),
            array('224.1.1.1/4', Type::T_MULTICAST),
            array('224.1.1.255/4', Type::T_MULTICAST),
            array('224.1.255.0/4', Type::T_MULTICAST),
            array('224.1.255.1/4', Type::T_MULTICAST),
            array('224.1.255.255/4', Type::T_MULTICAST),
            array('224.255.0.0/4', Type::T_MULTICAST),
            array('224.255.0.1/4', Type::T_MULTICAST),
            array('224.255.0.255/4', Type::T_MULTICAST),
            array('224.255.1.0/4', Type::T_MULTICAST),
            array('224.255.1.1/4', Type::T_MULTICAST),
            array('224.255.1.255/4', Type::T_MULTICAST),
            array('224.255.255.0/4', Type::T_MULTICAST),
            array('224.255.255.1/4', Type::T_MULTICAST),
            array('224.255.255.255/4', Type::T_MULTICAST),
            array('230.0.0.0/4', Type::T_MULTICAST),
            array('230.0.0.1/4', Type::T_MULTICAST),
            array('230.0.0.255/4', Type::T_MULTICAST),
            array('230.0.1.0/4', Type::T_MULTICAST),
            array('230.0.1.1/4', Type::T_MULTICAST),
            array('230.0.1.255/4', Type::T_MULTICAST),
            array('230.0.255.0/4', Type::T_MULTICAST),
            array('230.0.255.1/4', Type::T_MULTICAST),
            array('230.0.255.255/4', Type::T_MULTICAST),
            array('230.1.0.0/4', Type::T_MULTICAST),
            array('230.1.0.1/4', Type::T_MULTICAST),
            array('230.1.0.255/4', Type::T_MULTICAST),
            array('230.1.1.0/4', Type::T_MULTICAST),
            array('230.1.1.1/4', Type::T_MULTICAST),
            array('230.1.1.255/4', Type::T_MULTICAST),
            array('230.1.255.0/4', Type::T_MULTICAST),
            array('230.1.255.1/4', Type::T_MULTICAST),
            array('230.1.255.255/4', Type::T_MULTICAST),
            array('230.255.0.0/4', Type::T_MULTICAST),
            array('230.255.0.1/4', Type::T_MULTICAST),
            array('230.255.0.255/4', Type::T_MULTICAST),
            array('230.255.1.0/4', Type::T_MULTICAST),
            array('230.255.1.1/4', Type::T_MULTICAST),
            array('230.255.1.255/4', Type::T_MULTICAST),
            array('230.255.255.0/4', Type::T_MULTICAST),
            array('230.255.255.1/4', Type::T_MULTICAST),
            array('230.255.255.255/4', Type::T_MULTICAST),
            array('239.0.0.0/4', Type::T_MULTICAST),
            array('239.0.0.1/4', Type::T_MULTICAST),
            array('239.0.0.255/4', Type::T_MULTICAST),
            array('239.0.1.0/4', Type::T_MULTICAST),
            array('239.0.1.1/4', Type::T_MULTICAST),
            array('239.0.1.255/4', Type::T_MULTICAST),
            array('239.0.255.0/4', Type::T_MULTICAST),
            array('239.0.255.1/4', Type::T_MULTICAST),
            array('239.0.255.255/4', Type::T_MULTICAST),
            array('239.1.0.0/4', Type::T_MULTICAST),
            array('239.1.0.1/4', Type::T_MULTICAST),
            array('239.1.0.255/4', Type::T_MULTICAST),
            array('239.1.1.0/4', Type::T_MULTICAST),
            array('239.1.1.1/4', Type::T_MULTICAST),
            array('239.1.1.255/4', Type::T_MULTICAST),
            array('239.1.255.0/4', Type::T_MULTICAST),
            array('239.1.255.1/4', Type::T_MULTICAST),
            array('239.1.255.255/4', Type::T_MULTICAST),
            array('239.255.0.0/4', Type::T_MULTICAST),
            array('239.255.0.1/4', Type::T_MULTICAST),
            array('239.255.0.255/4', Type::T_MULTICAST),
            array('239.255.1.0/4', Type::T_MULTICAST),
            array('239.255.1.1/4', Type::T_MULTICAST),
            array('239.255.1.255/4', Type::T_MULTICAST),
            array('239.255.255.0/4', Type::T_MULTICAST),
            array('239.255.255.1/4', Type::T_MULTICAST),
            array('239.255.255.255/4', Type::T_MULTICAST),
            // 255.255.255.255/32
            array('255.255.255.255/32', Type::T_LIMITEDBROADCAST),
            // 240.0.0.0/4
            array('240.0.0.0/4', Type::T_RESERVED),
            array('240.0.0.1/4', Type::T_RESERVED),
            array('247.127.127.127/4', Type::T_RESERVED),
            array('248.128.128.128/4', Type::T_RESERVED),
            array('255.255.255.0/4', Type::T_RESERVED),
            array('255.255.255.253/4', Type::T_RESERVED),
            array('255.255.255.254/4', Type::T_RESERVED),
            // Public addresses
            array('2001:503:ba3e::2:30/32', Type::T_PUBLIC),
            array('216.58.212.68/32', Type::T_PUBLIC),
            array('31.11.33.139/32', Type::T_PUBLIC),
            array('104.25.25.33/32', Type::T_PUBLIC),
            // ::/128
            array('0000:0000:0000:0000:0000:0000:0000:0000/128', Type::T_UNSPECIFIED),
            // ::1/128
            array('0000:0000:0000:0000:0000:0000:0000:0001/128', Type::T_LOOPBACK),
            // 0000::/8
            array('0000:0000:0000:0000:0000:0000:0000:0002/8', Type::T_RESERVED),
            array('0000:0000:0000:0000:0000:0000:0000:ffff/8', Type::T_RESERVED),
            array('0001:0000:0000:0000:0000:0000:0000:0002/8', Type::T_RESERVED),
            array('00ff:0000:0000:0000:0000:0000:0000:0000/8', Type::T_RESERVED),
            array('00ff:0000:0000:0000:0000:0000:0000:0000/8', Type::T_RESERVED),
            array('00ff:0000:0000:0000:0000:0000:0000:ffff/8', Type::T_RESERVED),
            array('00ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/8', Type::T_RESERVED),
            // 0100::/64
            array('0100:0000:0000:0000:0000:0000:0000:0000/64', Type::T_DISCARDONLY),
            array('0100:0000:0000:0000:ffff:ffff:ffff:ffff/64', Type::T_DISCARDONLY),
            // 0100::/8
            array('0100:0000:0000:0001:0000:0000:0000:0000/8', Type::T_DISCARD),
            array('01ff:0000:0000:0000:0000:0000:0000:0000/8', Type::T_DISCARD),
            array('01ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/8', Type::T_DISCARD),
            // 0200::/7
            array('0200:0000:0000:0000:0000:0000:0000:0000/7', Type::T_RESERVED),
            array('0200:0000:0000:0000:0000:0000:0000:0009/7', Type::T_RESERVED),
            array('0200:0000:0000:0000:0000:000f:0000:0009/7', Type::T_RESERVED),
            array('03ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/7', Type::T_RESERVED),
            // 0400::/6
            array('0400:0000:0000:0000:0000:0000:0000:0000/6', Type::T_RESERVED),
            array('07ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/6', Type::T_RESERVED),
            // 0800::/5
            array('0800:0000:0000:0000:0000:0000:0000:0000/5', Type::T_RESERVED),
            array('0fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/5', Type::T_RESERVED),
            // 1000::/4
            array('1000:0000:0000:0000:0000:0000:0000:0000/4', Type::T_RESERVED),
            array('1fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/4', Type::T_RESERVED),
            // 2002::/48
            array('2002:0000:0000:0000:0000:0000:0000:0000/48', Type::T_UNSPECIFIED), //Assumed as 0.0.0.0 IPv4
            array('2002:0000:0000:ffff:ffff:ffff:ffff:ffff/48', Type::T_UNSPECIFIED), //Assumed as 0.0.0.0 IPv4
            array('2002:7f00:0001:0000:0000:0000:0000:0000/48', Type::T_LOOPBACK), //Assumed as 127.0.0.1 IPv4
            array('2002:7f00:0001:ffff:ffff:ffff:ffff:ffff/48', Type::T_LOOPBACK), //Assumed as 127.0.0.1 IPv4
            array('2002:ffff:ffff:0000:0000:0000:0000:0000/48', Type::T_LIMITEDBROADCAST), //Assumed as 255.255.255.255 IPv4
            array('2002:ffff:ffff:ffff:ffff:ffff:ffff:ffff/48', Type::T_LIMITEDBROADCAST), //Assumed as 255.255.255.255 IPv4
            // 2000::/3
            array('2000:0000:0000:0000:0000:0000:0000:0000/3', Type::T_PUBLIC),
            array('3fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::T_PUBLIC),
            // 4000::/3
            array('4000:0000:0000:0000:0000:0000:0000:0000/3', Type::T_RESERVED),
            array('5fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::T_RESERVED),
            // 6000::/3
            array('6000:0000:0000:0000:0000:0000:0000:0000/3', Type::T_RESERVED),
            array('7fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::T_RESERVED),
            // 8000::/3
            array('8000:0000:0000:0000:0000:0000:0000:0000/3', Type::T_RESERVED),
            array('9fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::T_RESERVED),
            // a000::/3
            array('a000:0000:0000:0000:0000:0000:0000:0000/3', Type::T_RESERVED),
            array('bfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::T_RESERVED),
            // c000::/3
            array('c000:0000:0000:0000:0000:0000:0000:0000/3', Type::T_RESERVED),
            array('dfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/3', Type::T_RESERVED),
            // e000::/4
            array('e000:0000:0000:0000:0000:0000:0000:0000/4', Type::T_RESERVED),
            array('efff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/4', Type::T_RESERVED),
            // f000::/5
            array('f000:0000:0000:0000:0000:0000:0000:0000/5', Type::T_RESERVED),
            array('f7ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/5', Type::T_RESERVED),
            // f800::/6
            array('f800:0000:0000:0000:0000:0000:0000:0000/6', Type::T_RESERVED),
            array('fbff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/6', Type::T_RESERVED),
            // fc00::/7
            array('fc00:0000:0000:0000:0000:0000:0000:0000/7', Type::T_PRIVATENETWORK),
            array('fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/7', Type::T_PRIVATENETWORK),
            // fe00::/9
            array('fe00:0000:0000:0000:0000:0000:0000:0000/9', Type::T_RESERVED),
            array('fe7f:ffff:ffff:ffff:ffff:ffff:ffff:ffff/9', Type::T_RESERVED),
            // fe80::/10
            array('fe80:0000:0000:0000:0000:0000:0000:0000/10', Type::T_LINKLOCAL_UNICAST),
            array('febf:ffff:ffff:ffff:ffff:ffff:ffff:ffff/10', Type::T_LINKLOCAL_UNICAST),
            // fec0::/10
            array('fec0:0000:0000:0000:0000:0000:0000:0000/10', Type::T_RESERVED),
            array('feff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/10', Type::T_RESERVED),
            // ff00::/8
            array('ff00:0000:0000:0000:0000:0000:0000:0000/8', Type::T_MULTICAST),
            array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/8', Type::T_MULTICAST),
        );
    }

    /**
     * @dataProvider ipProvider
     */
    public function testRangeTypes($range, $expectedType)
    {
        $ip = Factory::rangeFromString($range);
        $this->assertNotNull($ip, "'$range' has been detected as an invalid subnet, but it should be valid");
        $detectedType = $ip->getRangeType();
        $this->assertSame($expectedType, $detectedType, sprintf("'%s' has been detected as\n%s\ninstead of\n%s", $ip->toString(), Type::getName($detectedType), Type::getName($expectedType)));
    }

    public function rangeTypeNameProvider()
    {
        return array(
            array(null, 'Unknown type ()'),
            array('x', 'Unknown type (x)'),
            array(-1, 'Unknown type (-1)'),
        );
    }

    /**
     * @dataProvider rangeTypeNameProvider
     */
    public function testRangeTypeName($type, $expectedName)
    {
        $this->assertSame($expectedName, Type::getName($type));
    }
}
