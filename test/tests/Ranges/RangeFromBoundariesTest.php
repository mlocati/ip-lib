<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

class RangeFromBoundariesTest extends TestCase
{
    /**
     * @return array{string|mixed, string|mixed}[]
     */
    public function invalidProvider()
    {
        return array(
            array(null, null),
            array('127.0.0.1', '::1'),
            array(null, null),
            array('127.0.0.1', 'a'),
            array(' ', '127.0.0.1'),
            array('127.0.0.1', 0),
        );
    }

    /**
     * @dataProvider invalidProvider
     *
     * @param string|mixed $from
     * @param string|mixed $to
     *
     * @return void
     */
    public function testInvalid($from, $to)
    {
        $range = Factory::rangeFromBoundaries($from, $to);
        $this->assertNull($range, "Boundaries '" . json_encode($from) . "' -> '" . json_encode($to) . "' should not be resolved to an address");
        list($from, $to) = array($to, $from);
        $range = Factory::rangeFromBoundaries($from, $to);
        $this->assertNull($range, "Boundaries '" . json_encode($from) . "' -> '" . json_encode($to) . "' should not be resolved to an address");
    }

    /**
     * @return array{string, string|null, string}[]
     */
    public function validProvider()
    {
        return array(
            array('192.168.000.1', null, '192.168.0.1'),
            array('1.2.3.0', '1.2.3.1', '1.2.3.0/31'),
            array('1.2.3.128', '1.2.3.129', '1.2.3.128/31'),
            array('1.2.3.1', '1.2.3.2', '1.2.3.0/30'),
            array('1.2.3.0', '1.2.3.127', '1.2.3.0/25'),
            array('1.2.3.0', '1.2.3.64', '1.2.3.0/25'),
            array('1.2.3.63', '1.2.3.64', '1.2.3.0/25'),
            array('1.2.3.1', '1.2.3.127', '1.2.3.0/25'),
            array('1.2.3.0', '1.2.3.255', '1.2.3.0/24'),
            array('1.2.3.0', '1.2.3.128', '1.2.3.0/24'),
            array('1.2.0.0', '1.2.0.255', '1.2.0.0/24'),
            array('1.2.0.0', '1.2.0.1', '1.2.0.0/31'),
            array('1.2.0.0', '1.2.0.2', '1.2.0.0/30'),
            array('1.2.0.0', '1.2.0.3', '1.2.0.0/30'),
            array('1.2.0.0', '1.2.1.0', '1.2.0.0/23'),
            array('1.2.1.0', '1.2.0.0', '1.2.0.0/23'),
            array('128.0.0.0', '127.0.0.0', '0.0.0.0/0'),
            array('::1', null, '::1'),
            array('::', '::1', '::/127'),
            array('::1', '::', '::/127'),
            array('::1', '::1', '::1'),
            array('::1', '::2', '::/126'),
            array('::1', '::3', '::/126'),
            array('::2', '::3', '::2/127'),
            array('ffff::', 'ffff::1', 'ffff::/127'),
            array('ffff::1', 'ffff::1', 'ffff::1'),
            array('ffff::1', 'ffff::2', 'ffff::/126'),
            array('ffff::1', 'ffff::3', 'ffff::/126'),
            array('ffff::2', 'ffff::3', 'ffff::2/127'),
        );
    }

    /**
     * @dataProvider validProvider
     *
     * @param string $from
     * @param string|null $to
     * @param string $expected
     *
     * @return void
     */
    public function testValid($from, $to, $expected)
    {
        $range = Factory::rangeFromBoundaries($from, $to);
        $this->assertNotNull($range, "Boundaries '{$from}' -> '{$to}' should be resolved to an address");
        $this->assertSame($expected, (string) $range, "Boundaries '{$from}' -> '{$to}' should be resolved to '{$expected}' instead of {$range}");
        list($from, $to) = array($to, $from);
        $range = Factory::rangeFromBoundaries($from, $to);
        $this->assertNotNull($range, "Boundaries '{$from}' -> '{$to}' should be resolved to an address");
        $this->assertSame($expected, (string) $range, "Boundaries '{$from}' -> '{$to}' should be resolved to '{$expected}' instead of {$range}");
    }
}
