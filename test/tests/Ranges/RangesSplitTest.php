<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\ParseStringFlag;
use IPLib\Test\TestCase;

class RangesSplitTest extends TestCase
{
    public function invalidIPV4Provider()
    {
        $invalid = 'New networkPrefix must be larger than the base networkPrefix.';
        $max = 'New networkPrefix must be smaller than the maximum networkPrefix.';

        return array(
            array('1.2.3.4/0', 0, $invalid),
            array('1.2.3.4/1', 1, $invalid),
            array('1.2.3.4/10', 10, $invalid),
            array('1.2.3.4/20', 20, $invalid),
            array('1.2.3.4/24', 24, $invalid),
            array('1.2.3.4/25', 25, $invalid),
            array('1.2.3.4/32', 32, $invalid),

            array('1.2.3.4/0', 34, $max),
            array('1.2.3.4/1', 35, $max),
            array('1.2.3.4/10', 36, $max),
            array('1.2.3.4/20', 37, $max),
            array('1.2.3.4/24', 38, $max),
            array('1.2.3.4/25', 39, $max),
            array('1.2.3.4/32', 40, $max),

            array('1.2.3.4/32', 128, $max),
            array('1.2.3.4/32', 100, $max),
        );
    }

    /**
     * @dataProvider invalidIPV4Provider
     *
     * @param string $inputString
     * @param int $networkPrefix
     * @param mixed $expectedMessage
     */
    public function testInvalidSplitIPV4($inputString, $networkPrefix, $expectedMessage)
    {
        $range = Factory::parseRangeString($inputString, ParseStringFlag::IPV4SUBNET_MAYBE_COMPACT);

        // php 5.3, 5.4, 5.5 compatibility 🤮
        if (!method_exists($this, 'expectException')) {
            try {
                $range->split($networkPrefix);
                $this->fail('Expected exception not thrown');
            } catch (\Exception $e) {
                $this->assertEquals($expectedMessage, $e->getMessage());
            }

            return;
        }

        $this->expectException('\RuntimeException');
        $this->expectExceptionMessage($expectedMessage);

        $range->split($networkPrefix);
    }

    public function validIPV4Provider()
    {
        return array(
            array('1.2.3.4/24', 25, array(
                '1.2.3.0/25',
                '1.2.3.128/25',
            )),
            array('1.2.3.4/24', 26, array(
                '1.2.3.0/26',
                '1.2.3.64/26',
                '1.2.3.128/26',
                '1.2.3.192/26',
            )),
            array('1.2.3.4/24', 30, array(
                '1.2.3.0/30',
                '1.2.3.4/30',
                '1.2.3.8/30',
                '1.2.3.12/30',
                '1.2.3.16/30',
                '1.2.3.20/30',
                '1.2.3.24/30',
                '1.2.3.28/30',
                '1.2.3.32/30',
                '1.2.3.36/30',
                '1.2.3.40/30',
                '1.2.3.44/30',
                '1.2.3.48/30',
                '1.2.3.52/30',
                '1.2.3.56/30',
                '1.2.3.60/30',
                '1.2.3.64/30',
                '1.2.3.68/30',
                '1.2.3.72/30',
                '1.2.3.76/30',
                '1.2.3.80/30',
                '1.2.3.84/30',
                '1.2.3.88/30',
                '1.2.3.92/30',
                '1.2.3.96/30',
                '1.2.3.100/30',
                '1.2.3.104/30',
                '1.2.3.108/30',
                '1.2.3.112/30',
                '1.2.3.116/30',
                '1.2.3.120/30',
                '1.2.3.124/30',
                '1.2.3.128/30',
                '1.2.3.132/30',
                '1.2.3.136/30',
                '1.2.3.140/30',
                '1.2.3.144/30',
                '1.2.3.148/30',
                '1.2.3.152/30',
                '1.2.3.156/30',
                '1.2.3.160/30',
                '1.2.3.164/30',
                '1.2.3.168/30',
                '1.2.3.172/30',
                '1.2.3.176/30',
                '1.2.3.180/30',
                '1.2.3.184/30',
                '1.2.3.188/30',
                '1.2.3.192/30',
                '1.2.3.196/30',
                '1.2.3.200/30',
                '1.2.3.204/30',
                '1.2.3.208/30',
                '1.2.3.212/30',
                '1.2.3.216/30',
                '1.2.3.220/30',
                '1.2.3.224/30',
                '1.2.3.228/30',
                '1.2.3.232/30',
                '1.2.3.236/30',
                '1.2.3.240/30',
                '1.2.3.244/30',
                '1.2.3.248/30',
                '1.2.3.252/30',
            )),
            array('1.2.3.4/22', 23, array(
                '1.2.0.0/23',
                '1.2.2.0/23',
            )),
            array('192.168.1.0/30', 31, array(
                '192.168.1.0/31',
                '192.168.1.2/31',
            )),
            array('192.168.1.0/30', 32, array(
                '192.168.1.0/32',
                '192.168.1.1/32',
                '192.168.1.2/32',
                '192.168.1.3/32',
            )),
            array('192.168.1.0/29', 30, array(
                '192.168.1.0/30',
                '192.168.1.4/30',
            )),
            array('10.0.0.0/16', 20, array(
                '10.0.0.0/20',
                '10.0.16.0/20',
                '10.0.32.0/20',
                '10.0.48.0/20',
                '10.0.64.0/20',
                '10.0.80.0/20',
                '10.0.96.0/20',
                '10.0.112.0/20',
                '10.0.128.0/20',
                '10.0.144.0/20',
                '10.0.160.0/20',
                '10.0.176.0/20',
                '10.0.192.0/20',
                '10.0.208.0/20',
                '10.0.224.0/20',
                '10.0.240.0/20',
            )),
            array('192.168.1.0/24', 28, array(
                '192.168.1.0/28',
                '192.168.1.16/28',
                '192.168.1.32/28',
                '192.168.1.48/28',
                '192.168.1.64/28',
                '192.168.1.80/28',
                '192.168.1.96/28',
                '192.168.1.112/28',
                '192.168.1.128/28',
                '192.168.1.144/28',
                '192.168.1.160/28',
                '192.168.1.176/28',
                '192.168.1.192/28',
                '192.168.1.208/28',
                '192.168.1.224/28',
                '192.168.1.240/28',
            )),
        );
    }

    /**
     * @dataProvider validIPV4Provider
     *
     * @param string $inputString
     * @param int $networkPrefix
     * @param array $expectedValue
     */
    public function testValidSplitIPV4($inputString, $networkPrefix, $expectedValue)
    {
        $range = Factory::parseRangeString($inputString, ParseStringFlag::IPV4SUBNET_MAYBE_COMPACT);
        $this->assertInstanceOf('IPLib\Range\AbstractRange', $range);

        $splitRange = array_map(
            function ($range) {
                return (string) $range;
            },
            $range->split($networkPrefix)
        );

        $this->assertSame($splitRange, $expectedValue);
    }

    public function invalidIPV6Provider()
    {
        $invalid = 'New networkPrefix must be larger than the base networkPrefix.';
        $max = 'New networkPrefix must be smaller than the maximum networkPrefix.';

        return array(
            // Invalid cases (prefix not larger)
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/32', 32, $invalid),
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/48', 48, $invalid),
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/64', 64, $invalid),
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/128', 128, $invalid),

            // Invalid cases (prefix exceeds max for IPv6)
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/0', 129, $max),
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/128', 129, $max),
        );
    }

    /**
     * @dataProvider invalidIPV6Provider
     *
     * @param string $inputString
     * @param int $networkPrefix
     * @param $expectedMessage
     */
    public function testInvalidSplitIPV6($inputString, $networkPrefix, $expectedMessage)
    {
        $range = Factory::parseRangeString($inputString);

        // php 5.3, 5.4, 5.5 compatibility 🤮
        if (!method_exists($this, 'expectException')) {
            try {
                $range->split($networkPrefix);
                $this->fail('Expected exception not thrown');
            } catch (\Exception $e) {
                $this->assertEquals($expectedMessage, $e->getMessage());
            }

            return;
        }

        $this->expectException('\RuntimeException');
        $this->expectExceptionMessage($expectedMessage);

        $range->split($networkPrefix);
    }

    public function validIPV6Provider()
    {
        return array(
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/64', 66, array(
                '2001:db8:85a3::/66',
                '2001:db8:85a3:0:4000::/66',
                '2001:db8:85a3:0:8000::/66',
                '2001:db8:85a3:0:c000::/66',
            )),
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334/64', 70, array(
                '2001:db8:85a3::/70',
                '2001:db8:85a3:0:400::/70',
                '2001:db8:85a3:0:800::/70',
                '2001:db8:85a3:0:c00::/70',
                '2001:db8:85a3:0:1000::/70',
                '2001:db8:85a3:0:1400::/70',
                '2001:db8:85a3:0:1800::/70',
                '2001:db8:85a3:0:1c00::/70',
                '2001:db8:85a3:0:2000::/70',
                '2001:db8:85a3:0:2400::/70',
                '2001:db8:85a3:0:2800::/70',
                '2001:db8:85a3:0:2c00::/70',
                '2001:db8:85a3:0:3000::/70',
                '2001:db8:85a3:0:3400::/70',
                '2001:db8:85a3:0:3800::/70',
                '2001:db8:85a3:0:3c00::/70',
                '2001:db8:85a3:0:4000::/70',
                '2001:db8:85a3:0:4400::/70',
                '2001:db8:85a3:0:4800::/70',
                '2001:db8:85a3:0:4c00::/70',
                '2001:db8:85a3:0:5000::/70',
                '2001:db8:85a3:0:5400::/70',
                '2001:db8:85a3:0:5800::/70',
                '2001:db8:85a3:0:5c00::/70',
                '2001:db8:85a3:0:6000::/70',
                '2001:db8:85a3:0:6400::/70',
                '2001:db8:85a3:0:6800::/70',
                '2001:db8:85a3:0:6c00::/70',
                '2001:db8:85a3:0:7000::/70',
                '2001:db8:85a3:0:7400::/70',
                '2001:db8:85a3:0:7800::/70',
                '2001:db8:85a3:0:7c00::/70',
                '2001:db8:85a3:0:8000::/70',
                '2001:db8:85a3:0:8400::/70',
                '2001:db8:85a3:0:8800::/70',
                '2001:db8:85a3:0:8c00::/70',
                '2001:db8:85a3:0:9000::/70',
                '2001:db8:85a3:0:9400::/70',
                '2001:db8:85a3:0:9800::/70',
                '2001:db8:85a3:0:9c00::/70',
                '2001:db8:85a3:0:a000::/70',
                '2001:db8:85a3:0:a400::/70',
                '2001:db8:85a3:0:a800::/70',
                '2001:db8:85a3:0:ac00::/70',
                '2001:db8:85a3:0:b000::/70',
                '2001:db8:85a3:0:b400::/70',
                '2001:db8:85a3:0:b800::/70',
                '2001:db8:85a3:0:bc00::/70',
                '2001:db8:85a3:0:c000::/70',
                '2001:db8:85a3:0:c400::/70',
                '2001:db8:85a3:0:c800::/70',
                '2001:db8:85a3:0:cc00::/70',
                '2001:db8:85a3:0:d000::/70',
                '2001:db8:85a3:0:d400::/70',
                '2001:db8:85a3:0:d800::/70',
                '2001:db8:85a3:0:dc00::/70',
                '2001:db8:85a3:0:e000::/70',
                '2001:db8:85a3:0:e400::/70',
                '2001:db8:85a3:0:e800::/70',
                '2001:db8:85a3:0:ec00::/70',
                '2001:db8:85a3:0:f000::/70',
                '2001:db8:85a3:0:f400::/70',
                '2001:db8:85a3:0:f800::/70',
                '2001:db8:85a3:0:fc00::/70',
            )),
        );
    }

    /**
     * @dataProvider validIPV6Provider
     *
     * @param string $inputString
     * @param int $networkPrefix
     * @param array $expectedValue
     */
    public function testValidSplitIPV6($inputString, $networkPrefix, $expectedValue)
    {
        $range = Factory::parseRangeString($inputString);
        $this->assertInstanceOf('IPLib\Range\AbstractRange', $range);

        $splitRange = array_map(
            function ($range) {
                return (string) $range;
            },
            $range->split($networkPrefix)
        );

        $this->assertSame($splitRange, $expectedValue);
    }
}
