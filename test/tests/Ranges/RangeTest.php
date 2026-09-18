<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Range;
use IPLib\Test\TestCase;

class RangeTest extends TestCase
{
    /**
     * @return array{string|mixed}[]
     */
    public function invalidProvider()
    {
        return array(
            array(null),
            array(''),
            array('1.2.3.4'),
            array('1.2.3.4-'),
            array('-1.2.3.4'),
            array('1.2.3.4-::1'),
            array('1.2.3.4-1.2.3.5-1.2.3.6'),
        );
    }

    /**
     * @dataProvider invalidProvider
     *
     * @param string|mixed $range
     *
     * @return void
     */
    public function testInvalid($range)
    {
        $this->assertNull(Range::parseString($range));
    }

    /**
     * @return array{string, string, string, string, int, int}[]
     */
    public function validProvider()
    {
        return array(
            array('10.0.0.10-10.0.0.20', '10.0.0.10-10.0.0.20', '10.0.0.10', '10.0.0.20', 11, 27),
            array('10.0.0.20-10.0.0.10', '10.0.0.10-10.0.0.20', '10.0.0.10', '10.0.0.20', 11, 27),
            array('2001:db8::1-2001:db8::f', '2001:db8::1-2001:db8::f', '2001:db8::1', '2001:db8::f', 15, 124),
        );
    }

    /**
     * @dataProvider validProvider
     *
     * @param string $input
     * @param string $expectedString
     * @param string $expectedStart
     * @param string $expectedEnd
     * @param int $expectedSize
     * @param int $expectedPrefix
     *
     * @return void
     */
    public function testParseAndBoundaryMethods($input, $expectedString, $expectedStart, $expectedEnd, $expectedSize, $expectedPrefix)
    {
        $range = Range::parseString($input);
        $this->assertInstanceOf('IPLib\Range\Range', $range);
        $this->assertSame($expectedString, (string) $range);
        $this->assertSame($expectedStart, (string) $range->getStartAddress());
        $this->assertSame($expectedEnd, (string) $range->getEndAddress());
        $this->assertSame($expectedSize, $range->getSize());
        $this->assertSame($expectedSize, $range->getExactSize());
        $this->assertSame($expectedPrefix, $range->getNetworkPrefix());
        $this->assertTrue($range->contains(Factory::parseAddressString($expectedStart)));
        $this->assertTrue($range->contains(Factory::parseAddressString($expectedEnd)));
        $this->assertSame($expectedStart, (string) $range->getAddressAtOffset(0));
        $this->assertSame($expectedEnd, (string) $range->getAddressAtOffset(-1));
        $this->assertNull($range->getAddressAtOffset($expectedSize));
    }

    /**
     * @return void
     */
    public function testFactoryParsingAndConversions()
    {
        $range = Factory::parseRangeString('10.0.0.10-10.0.0.20');
        $this->assertInstanceOf('IPLib\Range\Range', $range);
        $this->assertSame('10.0.0.0/27', (string) $range->asSubnet());
        $this->assertNull($range->asPattern());
        $this->assertSame('255.255.255.224', (string) $range->getSubnetMask());

        $cidrRange = Range::parseString('192.168.0.0-192.168.0.255');
        $this->assertSame('192.168.0.0/24', (string) $cidrRange->asSubnet());
        $this->assertSame('192.168.0.*', (string) $cidrRange->asPattern());
    }

    /**
     * @return void
     */
    public function testSplitUsesOnlyContainedSubnets()
    {
        $range = Range::parseString('10.0.0.10-10.0.0.20');
        $subnets = $range->split(31);
        $this->assertSame(array('10.0.0.10/31', '10.0.0.12/31', '10.0.0.14/31', '10.0.0.16/31', '10.0.0.18/31', '10.0.0.20/32'), array_map('strval', $subnets));
        foreach ($subnets as $subnet) {
            $this->assertTrue($range->containsRange($subnet));
        }
    }

    /**
     * @return void
     */
    public function testInvalidSplitPrefix()
    {
        $range = Range::parseString('10.0.0.10-10.0.0.20');
        $exception = null;
        try {
            $range->split(26);
        } catch (\OutOfBoundsException $x) {
            $exception = $x;
        }
        $this->assertNotNull($exception);
    }
}
