<?php

namespace IPLib\Test\Addresses;

use IPLib\Address\Type;
use IPLib\Factory;
use IPLib\Test\TestCase;

class AddressTypeTest extends TestCase
{
    public function ipProvider()
    {
        return array(
            // 0.0.0.0/32
            array('0.0.0.0', Type::IPv4),
            // 0.0.0.0/8
            array('0.1.0.0', Type::IPv4),
            array('0.255.255.255', Type::IPv4),
            // 10.0.0.0/8
            array('10.0.0.0', Type::IPv4),
            array('10.1.0.0', Type::IPv4),
            array('10.255.255.255', Type::IPv4),
            // 127.0.0.0/8
            array('127.0.0.0', Type::IPv4),
            array('127.0.0.1', Type::IPv4),
            array('127.255.255.255', Type::IPv4),
            // 169.254.0.0/16
            array('169.254.0.0', Type::IPv4),
            array('169.254.1.0', Type::IPv4),
            array('169.254.255.255', Type::IPv4),
            // 172.16.0.0/12
            array('172.16.0.0', Type::IPv4),
            array('172.16.0.1', Type::IPv4),
            array('172.16.255.255', Type::IPv4),
            array('172.31.0.0', Type::IPv4),
            array('172.31.0.1', Type::IPv4),
            array('172.31.255.255', Type::IPv4),
            // 192.0.0.0/24
            array('192.0.0.0', Type::IPv4),
            array('192.0.0.1', Type::IPv4),
            array('192.0.0.255', Type::IPv4),
            // 192.0.2.0/24
            array('192.0.2.0', Type::IPv4),
            array('192.0.2.1', Type::IPv4),
            array('192.0.2.255', Type::IPv4),
            // 192.88.99.0/24
            array('192.88.99.0', Type::IPv4),
            array('192.88.99.1', Type::IPv4),
            array('192.88.99.255', Type::IPv4),
            // 192.168.0.0/16
            array('192.168.0.0', Type::IPv4),
            array('192.168.0.1', Type::IPv4),
            array('192.168.0.255', Type::IPv4),
            array('192.168.1.0', Type::IPv4),
            array('192.168.1.1', Type::IPv4),
            array('192.168.1.255', Type::IPv4),
            array('192.168.255.0', Type::IPv4),
            array('192.168.255.1', Type::IPv4),
            array('192.168.255.255', Type::IPv4),
            // 198.18.0.0/15
            array('198.18.0.0', Type::IPv4),
            array('198.18.0.1', Type::IPv4),
            array('198.18.0.255', Type::IPv4),
            array('198.18.1.0', Type::IPv4),
            array('198.18.1.1', Type::IPv4),
            array('198.18.1.255', Type::IPv4),
            array('198.18.255.0', Type::IPv4),
            array('198.18.255.1', Type::IPv4),
            array('198.18.255.255', Type::IPv4),
            array('198.19.0.0', Type::IPv4),
            array('198.19.0.1', Type::IPv4),
            array('198.19.0.255', Type::IPv4),
            array('198.19.1.0', Type::IPv4),
            array('198.19.1.1', Type::IPv4),
            array('198.19.1.255', Type::IPv4),
            array('198.19.255.0', Type::IPv4),
            array('198.19.255.1', Type::IPv4),
            array('198.19.255.255', Type::IPv4),
            // 198.51.100.0/24
            array('198.51.100.0', Type::IPv4),
            array('198.51.100.1', Type::IPv4),
            array('198.51.100.255', Type::IPv4),
            // 203.0.113.0/24
            array('203.0.113.0', Type::IPv4),
            array('203.0.113.1', Type::IPv4),
            array('203.0.113.255', Type::IPv4),
            // 224.0.0.0/4
            array('224.0.0.0', Type::IPv4),
            array('224.0.0.1', Type::IPv4),
            array('224.0.0.255', Type::IPv4),
            array('224.0.1.0', Type::IPv4),
            array('224.0.1.1', Type::IPv4),
            array('224.0.1.255', Type::IPv4),
            array('224.0.255.0', Type::IPv4),
            array('224.0.255.1', Type::IPv4),
            array('224.0.255.255', Type::IPv4),
            array('224.1.0.0', Type::IPv4),
            array('224.1.0.1', Type::IPv4),
            array('224.1.0.255', Type::IPv4),
            array('224.1.1.0', Type::IPv4),
            array('224.1.1.1', Type::IPv4),
            array('224.1.1.255', Type::IPv4),
            array('224.1.255.0', Type::IPv4),
            array('224.1.255.1', Type::IPv4),
            array('224.1.255.255', Type::IPv4),
            array('224.255.0.0', Type::IPv4),
            array('224.255.0.1', Type::IPv4),
            array('224.255.0.255', Type::IPv4),
            array('224.255.1.0', Type::IPv4),
            array('224.255.1.1', Type::IPv4),
            array('224.255.1.255', Type::IPv4),
            array('224.255.255.0', Type::IPv4),
            array('224.255.255.1', Type::IPv4),
            array('224.255.255.255', Type::IPv4),
            array('230.0.0.0', Type::IPv4),
            array('230.0.0.1', Type::IPv4),
            array('230.0.0.255', Type::IPv4),
            array('230.0.1.0', Type::IPv4),
            array('230.0.1.1', Type::IPv4),
            array('230.0.1.255', Type::IPv4),
            array('230.0.255.0', Type::IPv4),
            array('230.0.255.1', Type::IPv4),
            array('230.0.255.255', Type::IPv4),
            array('230.1.0.0', Type::IPv4),
            array('230.1.0.1', Type::IPv4),
            array('230.1.0.255', Type::IPv4),
            array('230.1.1.0', Type::IPv4),
            array('230.1.1.1', Type::IPv4),
            array('230.1.1.255', Type::IPv4),
            array('230.1.255.0', Type::IPv4),
            array('230.1.255.1', Type::IPv4),
            array('230.1.255.255', Type::IPv4),
            array('230.255.0.0', Type::IPv4),
            array('230.255.0.1', Type::IPv4),
            array('230.255.0.255', Type::IPv4),
            array('230.255.1.0', Type::IPv4),
            array('230.255.1.1', Type::IPv4),
            array('230.255.1.255', Type::IPv4),
            array('230.255.255.0', Type::IPv4),
            array('230.255.255.1', Type::IPv4),
            array('230.255.255.255', Type::IPv4),
            array('239.0.0.0', Type::IPv4),
            array('239.0.0.1', Type::IPv4),
            array('239.0.0.255', Type::IPv4),
            array('239.0.1.0', Type::IPv4),
            array('239.0.1.1', Type::IPv4),
            array('239.0.1.255', Type::IPv4),
            array('239.0.255.0', Type::IPv4),
            array('239.0.255.1', Type::IPv4),
            array('239.0.255.255', Type::IPv4),
            array('239.1.0.0', Type::IPv4),
            array('239.1.0.1', Type::IPv4),
            array('239.1.0.255', Type::IPv4),
            array('239.1.1.0', Type::IPv4),
            array('239.1.1.1', Type::IPv4),
            array('239.1.1.255', Type::IPv4),
            array('239.1.255.0', Type::IPv4),
            array('239.1.255.1', Type::IPv4),
            array('239.1.255.255', Type::IPv4),
            array('239.255.0.0', Type::IPv4),
            array('239.255.0.1', Type::IPv4),
            array('239.255.0.255', Type::IPv4),
            array('239.255.1.0', Type::IPv4),
            array('239.255.1.1', Type::IPv4),
            array('239.255.1.255', Type::IPv4),
            array('239.255.255.0', Type::IPv4),
            array('239.255.255.1', Type::IPv4),
            array('239.255.255.255', Type::IPv4),
            // 255.255.255.255/32
            array('255.255.255.255', Type::IPv4),
            // 240.0.0.0/4
            array('240.0.0.0', Type::IPv4),
            array('240.0.0.1', Type::IPv4),
            array('247.127.127.127', Type::IPv4),
            array('248.128.128.128', Type::IPv4),
            array('255.255.255.0', Type::IPv4),
            array('255.255.255.253', Type::IPv4),
            array('255.255.255.254', Type::IPv4),
            // Public addresses
            array('216.58.212.68', Type::IPv4),
            array('31.11.33.139', Type::IPv4),
            array('104.25.25.33', Type::IPv4),
            // ::/128
            array('0000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            // ::1/128
            array('0000:0000:0000:0000:0000:0000:0000:0001', Type::IPv6),
            // 0000::/8
            array('0000:0000:0000:0000:0000:0000:0000:0002', Type::IPv6),
            array('0000:0000:0000:0000:0000:0000:0000:ffff', Type::IPv6),
            array('0001:0000:0000:0000:0000:0000:0000:0002', Type::IPv6),
            array('00ff:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('00ff:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('00ff:0000:0000:0000:0000:0000:0000:ffff', Type::IPv6),
            array('00ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 0100::/64
            array('0100:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('0100:0000:0000:0000:ffff:ffff:ffff:ffff', Type::IPv6),
            // 0100::/8
            array('0100:0000:0000:0001:0000:0000:0000:0000', Type::IPv6),
            array('01ff:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('01ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 0200::/7
            array('0200:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('0200:0000:0000:0000:0000:0000:0000:0009', Type::IPv6),
            array('0200:0000:0000:0000:0000:000f:0000:0009', Type::IPv6),
            array('03ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 0400::/6
            array('0400:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('07ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 0800::/5
            array('0800:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('0fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 1000::/4
            array('1000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('1fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 2002::/16
            array('2002:0000:0000:0000:0000:0000:0000:0000', Type::IPv6), //Assumed as 0.0.0.0 IPv4
            array('2002:0000:0000:ffff:ffff:ffff:ffff:ffff', Type::IPv6), //Assumed as 0.0.0.0 IPv4
            array('2002:7f00:0001:0000:0000:0000:0000:0000', Type::IPv6), //Assumed as 127.0.0.1 IPv4
            array('2002:7f00:0001:ffff:ffff:ffff:ffff:ffff', Type::IPv6), //Assumed as 127.0.0.1 IPv4
            array('2002:ffff:ffff:0000:0000:0000:0000:0000', Type::IPv6), //Assumed as 255.255.255.255 IPv4
            array('2002:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6), //Assumed as 255.255.255.255 IPv4
            // 2000::/3
            array('2000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('3fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 4000::/3
            array('4000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('5fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 6000::/3
            array('6000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('7fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // 8000::/3
            array('8000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('9fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // a000::/3
            array('a000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('bfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // c000::/3
            array('c000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('dfff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // e000::/4
            array('e000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('efff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // f000::/5
            array('f000:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('f7ff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // f800::/6
            array('f800:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('fbff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // fc00::/7
            array('fc00:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // fe00::/9
            array('fe00:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('fe7f:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // fe80::/10
            array('fe80:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('febf:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // fec0::/10
            array('fec0:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('feff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            // ff00::/8
            array('ff00:0000:0000:0000:0000:0000:0000:0000', Type::IPv6),
            array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', Type::IPv6),
            array('2001:503:ba3e::2:30', Type::IPv6),
        );
    }

    /**
     * @dataProvider ipProvider
     *
     * @param string $address
     * @param int $expectedType
     */
    public function testAddressTypes($address, $expectedType)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNotNull($ip, "'{$address}' has been detected as an invalid IP, but it should be valid");
        $detectedType = $ip->getAddressType();
        $this->assertSame($expectedType, $detectedType, sprintf("'%s' has been detected as\n%s\ninstead of\n%s", $ip->toString(), Type::getName($detectedType), Type::getName($expectedType)));
    }

    public function addressTypeNameProvider()
    {
        return array(
            array(null, 'Unknown type ()'),
            array('x', 'Unknown type (x)'),
            array(-1, 'Unknown type (-1)'),
        );
    }

    /**
     * @dataProvider addressTypeNameProvider
     *
     * @param int|mixed $type
     * @param string $expectedName
     */
    public function testAddressTypeName($type, $expectedName)
    {
        $this->assertSame($expectedName, Type::getName($type));
    }
}
