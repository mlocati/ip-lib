<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

class RangeSizeTest extends TestCase
{
    public function rangesProvider()
    {
        return array(
            array('8.8.8.8', 1),
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334', 1),

            array('8.8.8.8/32', 1),
            array('8.8.8.8/31', 2),
            array('0.0.0.0/1', PHP_INT_SIZE > 4 ? 0x80000000 : 2147483648.0, PHP_INT_SIZE > 4 ? null : '2147483648'),
            array('0.0.0.0/0', PHP_INT_SIZE > 4 ? 0x100000000 : 4294967296.0, PHP_INT_SIZE > 4 ? null : '4294967296'),

            array('100::/128', 1),
            array('100::/127', 2),
            array('100::/98', 1073741824),
            array('100::/97', PHP_INT_SIZE > 4 ? 2147483648 : 2147483648.0, PHP_INT_SIZE > 4 ? null : '2147483648'),
            array('0::/1', 170141183460469231731687303715884105728.0, '170141183460469231731687303715884105728'),
            array('::/0', 340282366920938463463374607431768211456.0, '340282366920938463463374607431768211456'),

            array('172.16.0.*', 0x100),
            array('172.*.*.*', 0x1000000),
            array('*.*.*.*', PHP_INT_SIZE > 4 ? 0x100000000 : 4294967296.0, PHP_INT_SIZE > 4 ? null : '4294967296'),
            array('2001:0db8:85a3:0000:0000:8a2e:0370:*', 0x10000),
            array('2001:0db8:85a3:0000:0000:*:*:*', PHP_INT_SIZE > 4 ? 0x1000000000000 : 281474976710656.0, PHP_INT_SIZE > 4 ? null : '281474976710656'),
            array('*:*:*:*:*:*:*:*', 340282366920938463463374607431768211456.0, '340282366920938463463374607431768211456'),
        );
    }

    /**
     * @dataProvider rangesProvider
     *
     * @param string $addressRange
     * @param int|float $expectedSize
     * @param int|string|null $expectedExactSize
     */
    public function testSize($addressRange, $expectedSize, $expectedExactSize = null)
    {
        $range = Factory::rangeFromString($addressRange);
        $actualSize = $range->getSize();
        $this->assertSame($expectedSize, $actualSize, 'getSize()');
        if ($expectedExactSize === null) {
            $expectedExactSize = $expectedSize;
        }
        $actualExactSize = $range->getExactSize();
        $this->assertSame($expectedExactSize, $actualExactSize, 'getExactSize()');
    }
}
