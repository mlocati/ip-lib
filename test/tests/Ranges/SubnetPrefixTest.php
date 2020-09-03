<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Subnet;
use IPLib\Test\TestCase;

class SubnetPrefixTest extends TestCase
{
    public function ipProvider()
    {
        $tests = array();

        // IPv4
        for ($i = 0; $i <= 32; $i++) {
            $tests[] = array('0.0.0.0/' . $i, $i);
        }

        // IPv6
        for ($i = 0; $i <= 128; $i++) {
            $tests[] = array('0000:0000:0000:0000:0000:0000:0000:0000/' . $i, $i);
        }

        return $tests;
    }

    /**
     * @dataProvider ipProvider
     *
     * @param string $rangeString
     * @param int $expectedPrefix
     */
    public function testSubnetPrefix($rangeString, $expectedPrefix)
    {
        /** @var Subnet $range */
        $range = Factory::rangeFromString($rangeString);
        $this->assertNotNull($range, "'{$rangeString}' has been detected as an invalid subnet, but it should be valid");
        $detectedPrefix = $range->getNetworkPrefix();
        $this->assertSame($expectedPrefix, $detectedPrefix, "'{$rangeString}' has been detected prefix as\n{$detectedPrefix}\ninstead of\n{$expectedPrefix}");
    }
}
