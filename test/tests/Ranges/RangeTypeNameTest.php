<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Type;
use IPLib\Test\TestCase;

class RangeTypeNameTest extends TestCase
{
    /**
     * @return array{string, int}[]
     */
    public function rangeTypeNameProvider()
    {
        return array(
            array('8.8.8.0/24', Type::T_PUBLIC),
            array('10.0.0.0/8', Type::T_PRIVATENETWORK),
            array('2a03:2880::/32', Type::T_PUBLIC),
            array('fc00::/7', Type::T_PRIVATENETWORK),
            // Crosses multiple range types: named after its starting address
            array('224.0.0.0/4', Type::T_MULTICAST),
            array('0.0.0.0/0', Type::T_UNSPECIFIED),
        );
    }

    /**
     * @dataProvider rangeTypeNameProvider
     *
     * @param string $rangeString
     * @param int $expectedType
     *
     * @return void
     */
    public function testRangeTypeName($rangeString, $expectedType)
    {
        $range = Factory::parseRangeString($rangeString);
        $this->assertNotNull($range);
        $this->assertSame(Type::getName($expectedType), $range->getRangeTypeName());
    }
}
