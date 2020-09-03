<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Subnet;
use IPLib\Test\TestCase;

class ConversionTest extends TestCase
{
    public function rangeConversionProvider()
    {
        return array(
            // IPv4
            array('1.2.3.4/0', '*.*.*.*', '0.0.0.0/0'),
            array('1.2.3.4/1', ''),
            array('1.2.3.4/2', ''),
            array('1.2.3.4/3', ''),
            array('1.2.3.4/4', ''),
            array('1.2.3.4/5', ''),
            array('1.2.3.4/6', ''),
            array('1.2.3.4/7', ''),
            array('1.2.3.4/8', '1.*.*.*', '1.0.0.0/8'),
            array('1.2.3.4/9', ''),
            array('1.2.3.4/15', ''),
            array('1.2.3.4/16', '1.2.*.*', '1.2.0.0/16'),
            array('1.2.3.4/17', ''),
            array('1.2.3.4/23', ''),
            array('1.2.3.4/24', '1.2.3.*', '1.2.3.0/24'),
            array('1.2.3.4/25', ''),
            array('1.2.3.4/31', ''),
            array('1.2.3.4/32', '1.2.3.4'),
            // IPv6
            array('1:2:3:4:5:6:7:8/0', '*:*:*:*:*:*:*:*', '::/0'),
            array('1:2:3:4:5:6:7:8/1', ''),
            array('1:2:3:4:5:6:7:8/2', ''),
            array('1:2:3:4:5:6:7:8/3', ''),
            array('1:2:3:4:5:6:7:8/4', ''),
            array('1:2:3:4:5:6:7:8/5', ''),
            array('1:2:3:4:5:6:7:8/6', ''),
            array('1:2:3:4:5:6:7:8/7', ''),
            array('1:2:3:4:5:6:7:8/8', ''),
            array('1:2:3:4:5:6:7:8/9', ''),
            array('1:2:3:4:5:6:7:8/10', ''),
            array('1:2:3:4:5:6:7:8/11', ''),
            array('1:2:3:4:5:6:7:8/12', ''),
            array('1:2:3:4:5:6:7:8/13', ''),
            array('1:2:3:4:5:6:7:8/14', ''),
            array('1:2:3:4:5:6:7:8/15', ''),
            array('1:2:3:4:5:6:7:8/16', '1:*:*:*:*:*:*:*', '1::/16'),
            array('1:2:3:4:5:6:7:8/17', ''),
            array('1:2:3:4:5:6:7:8/31', ''),
            array('1:2:3:4:5:6:7:8/32', '1:2:*:*:*:*:*:*', '1:2::/32'),
            array('1:2:3:4:5:6:7:8/33', ''),
            array('1:2:3:4:5:6:7:8/47', ''),
            array('1:2:3:4:5:6:7:8/48', '1:2:3:*:*:*:*:*', '1:2:3::/48'),
            array('1:2:3:4:5:6:7:8/49', ''),
            array('1:2:3:4:5:6:7:8/63', ''),
            array('1:2:3:4:5:6:7:8/64', '1:2:3:4:*:*:*:*', '1:2:3:4::/64'),
            array('1:2:3:4:5:6:7:8/65', ''),
            array('1:2:3:4:5:6:7:8/79', ''),
            array('1:2:3:4:5:6:7:8/80', '1:2:3:4:5:*:*:*', '1:2:3:4:5::/80'),
            array('1:2:3:4:5:6:7:8/81', ''),
            array('1:2:3:4:5:6:7:8/95', ''),
            array('1:2:3:4:5:6:7:8/96', '1:2:3:4:5:6:*:*', '1:2:3:4:5:6::/96'),
            array('1:2:3:4:5:6:7:8/97', ''),
            array('1:2:3:4:5:6:7:8/111', ''),
            array('1:2:3:4:5:6:7:8/112', '1:2:3:4:5:6:7:*', '1:2:3:4:5:6:7:0/112'),
            array('1:2:3:4:5:6:7:8/113', ''),
            array('1:2:3:4:5:6:7:8/127', ''),
            array('1:2:3:4:5:6:7:8/128', '1:2:3:4:5:6:7:8'),
        );
    }

    /**
     * @dataProvider rangeConversionProvider
     *
     * @param string $subnet
     * @param string $pattern
     * @param string|null $subnet2
     */
    public function testRangeConversion($subnet, $pattern, $subnet2 = null)
    {
        /** @var Subnet $subnetRange */
        $subnetRange = Factory::rangeFromString($subnet);
        $this->assertInstanceOf('IPLib\Range\Subnet', $subnetRange);
        $patternRange = $subnetRange->asPattern();
        if ($pattern === '') {
            $this->assertNull($patternRange);
        } else {
            $this->assertInstanceOf('IPLib\Range\Pattern', $patternRange);
            $this->assertSame($pattern, (string) $patternRange);
            $subnetRange2 = $patternRange->asSubnet();
            $this->assertInstanceOf('IPLib\Range\Subnet', $subnetRange2);
            if ($subnet2 === null) {
                $subnet2 = $subnet;
            }
            $this->assertSame($subnet2, (string) $subnetRange2);
        }
    }
}
