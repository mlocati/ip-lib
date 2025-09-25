<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\ParseStringFlag;
use IPLib\Test\TestCase;

/**
 * @see http://publibn.boulder.ibm.com/doc_link/en_US/a_doc_lib/libs/commtrf2/inet_addr.htm
 */
class CompactSubnetTest extends TestCase
{
    /**
     * @return array{string, string, string, string, 4?: bool}[]
     */
    public function provideTestCases()
    {
        return array(
            array('1.2.3.4/32', '1.2.3.4/32', '1.2.3.4', '1.2.3.4', false),
            array('1.2.3.4/28', '1.2.3.0/28', '1.2.3.0', '1.2.3.15', false),
            array('1.2.3/24', '1.2.3.0/24', '1.2.3.0', '1.2.3.255'),
            array('1.2.3/20', '1.2.0.0/20', '1.2.0.0', '1.2.15.255'),
            array('1.2/16', '1.2.0.0/16', '1.2.0.0', '1.2.255.255'),
            array('1.2/12', '1.0.0.0/12', '1.0.0.0', '1.15.255.255'),
            array('1/8', '1.0.0.0/8', '1.0.0.0', '1.255.255.255'),
            array('1/4', '0.0.0.0/4', '0.0.0.0', '15.255.255.255'),
            array('0/0', '0.0.0.0/0', '0.0.0.0', '255.255.255.255'),
        );
    }

    /**
     * @dataProvider provideTestCases
     *
     * @param string $inputString
     * @param string $expectedRangeString
     * @param string $expectedStartAddressString
     * @param string $expectedEndAddressString
     * @param bool $inputIsCompact
     *
     * @return void
     */
    public function testBoundaries($inputString, $expectedRangeString, $expectedStartAddressString, $expectedEndAddressString, $inputIsCompact = true)
    {
        if ($inputIsCompact) {
            $range = Factory::parseRangeString($inputString);
            $this->assertNull($range);
        }
        $range = Factory::parseRangeString($inputString, ParseStringFlag::IPV4SUBNET_MAYBE_COMPACT);
        $this->assertInstanceOf('IPLib\Range\Subnet', $range);
        $this->assertSame($expectedRangeString, (string) $range);
        $this->assertSame((string) $range->getStartAddress(), $expectedStartAddressString, 'Start address');
        $this->assertSame((string) $range->getEndAddress(), $expectedEndAddressString, 'End address');
    }
}
