<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Test\TestCase;

class AddressAtOffsetTest extends TestCase
{
    /**
     * @return array{string, int|numeric-string|mixed, string|null}[]
     */
    public function ipProvider()
    {
        return array(
            array('127.0.0.0/16', 256, '127.0.1.0'),
            array('127.0.0.0/16', -1, '127.0.255.255'),
            array('127.0.0.0/24', 256, null),
            array('127.0.0.0/16', 0, '127.0.0.0'),
            array('0.0.0.0/0', '4294967295', '255.255.255.255'),
            array('::ff00/120', 0, '::ff00'),
            array('::ff00/120', 16, '::ff10'),
            array('::ff00/120', 100, '::ff64'),
            array('::ff00/120', 256, null),
            array('::ff00/120', -1, '::ffff'),
            array('::ff00/120', -16, '::fff0'),
            array('::ff00/120', -256, '::ff00'),
            array('::ff00/120', -257, null),
            array('255.255.255.255/32', 1, null),
            array('::ff00/120', '0', '::ff00'),
            array('::ff00/120', 'invalid', null),
            array('::/0', '340282366920938463463374607431768211454', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe'),
            array('::/0', '340282366920938463463374607431768211455', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'),
            array('::/0', '340282366920938463463374607431768211456', null),
            array('::/1', '340282366920938463463374607431768211455', null),
            array('::/1', '170141183460469231731687303715884105727', '7fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'),
            array('::/1', '170141183460469231731687303715884105728', null),
        );
    }

    /**
     * @dataProvider ipProvider
     *
     * @param string $rangeString
     * @param int|numeric-string|mixed $n
     * @param string|null $expected
     *
     * @return void
     */
    public function testAddressAtOffset($rangeString, $n, $expected)
    {
        $range = Factory::rangeFromString($rangeString);
        $this->assertInstanceOf('IPLib\Range\RangeInterface', $range);
        $result = $range->getAddressAtOffset($n);
        if ($result !== null) {
            $result = $result->toString();
        }

        $expectedString = $expected;
        if ($expected === null) {
            $expectedString = 'NULL';
        }

        $this->assertSame($expected, $result, "'{$rangeString}' with offset " . json_encode($n) . " must be '{$expectedString}'");
    }
}
