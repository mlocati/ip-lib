<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

class RangesFromBoundariesTest extends TestCase
{
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
     */
    public function testInvalid($from, $to)
    {
        $range = Factory::rangesFromBoundaries($from, $to);
        $this->assertNull($range, "Boundaries '{$from}' -> '{$to}' should not be resolved to an address");
        list($from, $to) = array($to, $from);
        $range = Factory::rangesFromBoundaries($from, $to);
        $this->assertNull($range, "Boundaries '{$from}' -> '{$to}' should not be resolved to an address");
    }

    public function validProvider()
    {
        return array(
            array('192.168.000.1', null, array('192.168.0.1/32')),
            array('1.2.3.0', '1.2.3.1', array('1.2.3.0/31')),
            array('1.2.3.128', '1.2.3.129', array('1.2.3.128/31')),
            array('1.2.3.1', '1.2.3.2', array('1.2.3.1/32', '1.2.3.2/32')),
            array('1.2.3.0', '1.2.3.127', array('1.2.3.0/25')),
            array('1.2.3.0', '1.2.3.64', array('1.2.3.0/26', '1.2.3.64/32')),
            array('1.2.3.63', '1.2.3.64', array('1.2.3.63/32', '1.2.3.64/32')),
            array('1.2.3.1', '1.2.3.127', array('1.2.3.1/32', '1.2.3.2/31', '1.2.3.4/30', '1.2.3.8/29', '1.2.3.16/28', '1.2.3.32/27', '1.2.3.64/26')),
            array('1.2.3.0', '1.2.3.255', array('1.2.3.0/24')),
            array('1.2.3.0', '1.2.3.128', array('1.2.3.0/25', '1.2.3.128/32')),
            array('1.2.0.0', '1.2.0.255', array('1.2.0.0/24')),
            array('1.2.0.0', '1.2.0.1', array('1.2.0.0/31')),
            array('1.2.0.0', '1.2.0.2', array('1.2.0.0/31', '1.2.0.2/32')),
            array('1.2.0.0', '1.2.0.3', array('1.2.0.0/30')),
            array('1.2.0.0', '1.2.1.0', array('1.2.0.0/24', '1.2.1.0/32')),
            array('128.0.0.0', '127.0.0.0', array('127.0.0.0/8', '128.0.0.0/32')),
            array('192.168.0.1', '192.168.0.78', array('192.168.0.1/32', '192.168.0.2/31', '192.168.0.4/30', '192.168.0.8/29', '192.168.0.16/28', '192.168.0.32/27', '192.168.0.64/29', '192.168.0.72/30', '192.168.0.76/31', '192.168.0.78/32')),
            array('::1', null, array('::1/128')),
            array('::', '::1', array('::/127')),
            array('::1', '::1', array('::1/128')),
            array('::1', '::2', array('::1/128', '::2/128')),
            array('::1', '::3', array('::1/128', '::2/127')),
            array('::2', '::3', array('::2/127')),
            array('ffff::', 'ffff::1', array('ffff::/127')),
            array('ffff::1', 'ffff::1', array('ffff::1/128')),
            array('ffff::1', 'ffff::2', array('ffff::1/128', 'ffff::2/128')),
            array('ffff::1', 'ffff::3', array('ffff::1/128', 'ffff::2/127')),
            array('ffff::2', 'ffff::3', array('ffff::2/127')),
        );
    }

    /**
     * @dataProvider validProvider
     *
     * @param string $from
     * @param string|null $to
     * @param string[] $expected
     */
    public function testValid($from, $to, array $expected)
    {
        $ranges = Factory::rangesFromBoundaries($from, $to);
        $this->assertNotNull($ranges, "Boundaries '{$from}' -> '{$to}' should be resolved to an address");
        $this->assertSameIPRanges($expected, $ranges);
        foreach ($ranges as $range) {
            $range2 = Factory::rangeFromString((string) $range);
            $this->assertSame((string) $range, (string) $range2, 'Same range');
            $this->assertSame((string) $range->getStartAddress(), (string) $range2->getStartAddress(), 'Same start address');
            $this->assertSame((string) $range->getEndAddress(), (string) $range2->getEndAddress(), 'Same end address');
        }
        list($from, $to) = array($to, $from);
        $ranges = Factory::rangesFromBoundaries($from, $to);
        $this->assertNotNull($ranges, "Boundaries '{$from}' -> '{$to}' should be resolved to an address");
        $this->assertSameIPRanges($expected, $ranges);
    }

    private function assertSameIPRanges(array $expected, array $calculatedInstances)
    {
        $calculatedStrings = array();
        foreach ($calculatedInstances as $calculatedInstance) {
            $this->assertSame('IPLib\Range\Subnet', get_class($calculatedInstance));
            $calculatedStrings[] = (string) $calculatedInstance;
        }
        $this->assertSame($expected, $calculatedStrings);
    }
}
