<?php

namespace IPLib\Test\Services;

use IPLib\Address\AddressInterface;
use IPLib\Address\IPv4;
use IPLib\Address\IPv6;
use IPLib\Factory;
use IPLib\Service\RangesFromBoundaryCalculator;
use IPLib\Test\TestCase;

class RangesFromBoundaryCalculatorTest extends TestCase
{
    /**
     * @var \IPLib\Service\RangesFromBoundaryCalculator[]
     */
    private static $calculators = array();

    /**
     * @dataProvider provideInvalidCases
     *
     * @param \IPLib\Service\RangesFromBoundaryCalculator $calculator
     * @param \IPLib\Address\AddressInterface $from
     * @param \IPLib\Address\AddressInterface $to
     *
     * @return void
     */
    public function testInvalid(RangesFromBoundaryCalculator $calculator, AddressInterface $from, AddressInterface $to)
    {
        $ranges = $calculator->getRanges($from, $to);
        $this->assertNull($ranges);
    }

    /**
     * @return array{\IPLib\Service\RangesFromBoundaryCalculator, \IPLib\Address\AddressInterface, \IPLib\Address\AddressInterface}[]
     */
    public function provideInvalidCases()
    {
        $ipv4Calculator = self::getCalculator(IPv4::getNumberOfBits());
        $ipv6Calculator = self::getCalculator(IPv6::getNumberOfBits());
        $ipv4 = Factory::addressFromString('127.0.0.1');
        /** @var IPv4 $ipv4 */
        $ipv6 = Factory::addressFromString('::');
        /** @var IPv6 $ipv6 */

        return array(
            array(
                $ipv4Calculator,
                $ipv4,
                $ipv6,
            ),
            array(
                $ipv4Calculator,
                $ipv6,
                $ipv4,
            ),
            array(
                $ipv4Calculator,
                $ipv6,
                $ipv6,
            ),
            array(
                $ipv6Calculator,
                $ipv4,
                $ipv4,
            ),
            array(
                $ipv6Calculator,
                $ipv4,
                $ipv6,
            ),
            array(
                $ipv6Calculator,
                $ipv6,
                $ipv4,
            ),
        );
    }

    /**
     * @dataProvider provideValidCases
     *
     * @param string $from
     * @param string $to
     * @param string[] $expectedRanges
     *
     * @return void
     */
    public function testValid($from, $to, array $expectedRanges)
    {
        $fromAddress = Factory::addressFromString($from);
        $this->assertInstanceof('IPLib\Address\AddressInterface', $fromAddress);
        $toAddress = Factory::addressFromString($to);
        $this->assertInstanceof('IPLib\Address\AddressInterface', $toAddress);
        $calculator = self::getCalculator($fromAddress->getNumberOfBits());
        $rangesAsStrings = array();
        $ranges = $calculator->getRanges($fromAddress, $toAddress);
        $this->assertNotNull($ranges);
        foreach ($ranges as $range) {
            $this->assertInstanceOf('IPLib\Range\Subnet', $range);
            $rangesAsStrings[] = (string) $range;
        }
        $this->assertSame($rangesAsStrings, $expectedRanges);
    }

    /**
     * @return array{string, string, string[]}[]
     */
    public function provideValidCases()
    {
        return array(
            array('127.0.0.0', '127.0.0.0', array('127.0.0.0/32')),
            array('127.0.0.0', '127.0.0.1', array('127.0.0.0/31')),
            array('127.0.0.1', '127.0.0.0', array('127.0.0.0/31')),
            array('127.0.0.1', '127.0.0.1', array('127.0.0.1/32')),
            array('::', '::', array('::/128')),
            array('::', '::1', array('::/127')),
            array('::1', '::', array('::/127')),
            array('::1', '::1', array('::1/128')),
            array('192.168.1.0', '192.168.1.9', array('192.168.1.0/29', '192.168.1.8/31')),
            array('211.1.1.5', '211.1.1.23', array('211.1.1.5/32', '211.1.1.6/31', '211.1.1.8/29', '211.1.1.16/29')),
            array('211.1.1.5', '211.1.1.6', array('211.1.1.5/32', '211.1.1.6/32')),
            array('1.0.0.0', '129.0.0.0', array('1.0.0.0/8', '2.0.0.0/7', '4.0.0.0/6', '8.0.0.0/5', '16.0.0.0/4', '32.0.0.0/3', '64.0.0.0/2', '128.0.0.0/8', '129.0.0.0/32')),
        );
    }

    /**
     * @param int $numOfBits
     *
     * @return \IPLib\Service\RangesFromBoundaryCalculator
     */
    private static function getCalculator($numOfBits)
    {
        if (!isset(self::$calculators[$numOfBits])) {
            self::$calculators[$numOfBits] = new RangesFromBoundaryCalculator($numOfBits);
        }

        return self::$calculators[$numOfBits];
    }
}
