<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Single;
use IPLib\Test\TestCase;

class SingleTest extends TestCase
{
    /**
     * @return array{string|mixed}[]
     */
    public function invalidProvider()
    {
        return array(
            array(null),
            array(false),
            array(''),
            array(array()),
            array('*/1'),
            array('0.0.0.256'),
            array('127.0.0.-1'),
            array(':::'),
            array(':'),
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
        $this->assertNull(Single::fromString($range), json_encode($range) . " has been recognized as a single range, but it shouldn't");
    }

    /**
     * @return array{string, string, string}[]
     */
    public function validProvider()
    {
        return array(
            array('0.0.0.0', '0.0.0.0', '000.000.000.000'),
            array('0.0.0.0', '0.0.0.0', '000.000.000.000'),
            array('::', '::', '0000:0000:0000:0000:0000:0000:0000:0000'),
            array('0000::ff:00', '::ff:0', '0000:0000:0000:0000:0000:0000:00ff:0000'),
        );
    }

    /**
     * @dataProvider validProvider
     *
     * @param string $range
     * @param string $short
     * @param string $long
     *
     * @return void
     */
    public function testValid($range, $short, $long)
    {
        $ex = Factory::rangeFromString($range);
        $this->assertNotNull($ex, "'{$range}' has not been recognized as a range, but it should");
        $this->assertInstanceOf('IPLib\Range\Single', $ex, "'{$range}' has been recognized as a range, but not a single range");
        $this->assertSame($ex->containsRange($ex), true);
        $this->assertSame($short, $ex->toString(false));
        $this->assertSame($long, $ex->toString(true));
    }
}
