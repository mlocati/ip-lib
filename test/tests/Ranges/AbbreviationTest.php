<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

/**
 * @see http://publibn.boulder.ibm.com/doc_link/en_US/a_doc_lib/libs/commtrf2/inet_addr.htm
 */
class AbbreviationTest extends TestCase
{
    public function boundariesProvider()
    {
        return array(
            array('1.2.3.4/32', '1.2.3.4', '1.2.3.4'),
            array('1.2.3.4/28', '1.2.3.0', '1.2.3.15'),
            array('1.2.3/24', '1.2.3.0', '1.2.3.255'),
            array('1.2.3/20', '1.2.0.0', '1.2.15.255'),
            array('1.2/16', '1.2.0.0', '1.2.255.255'),
            array('1.2/12', '1.0.0.0', '1.15.255.255'),
            array('1/8', '1.0.0.0', '1.255.255.255'),
            array('1/4', '0.0.0.0', '15.255.255.255'),
            array('0/0', '0.0.0.0', '255.255.255.255'),
        );
    }

    /**
     * @dataProvider boundariesProvider
     *
     * @param string $rangeString
     * @param string $startAddressString
     * @param string $endAddressString
     */
    public function testBoundaries($rangeString, $startAddressString, $endAddressString)
    {
        $range = Factory::rangeFromString($rangeString, true);
        $this->assertInstanceOf('IPLib\Range\RangeInterface', $range);
        $this->assertSame((string) $range->getStartAddress(), $startAddressString, "Checking start address of {$rangeString}");
        $this->assertSame((string) $range->getEndAddress(), $endAddressString, "Checking end address of {$rangeString}");
    }
}
