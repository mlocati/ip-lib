<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Single;

class SingleTest extends \PHPUnit_Framework_TestCase
{
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
     */
    public function testInvalid($range)
    {
        $str = @strval($range);
        $this->assertNull(Single::fromString($range), "'$str' has been recognized as a single range, but it shouldn't");
    }

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
     */
    public function testValid($range, $short, $long)
    {
        $str = @strval($range);
        $ex = Factory::rangeFromString($range);
        $this->assertNotNull($ex, "'$str' has not been recognized as a range, but it should");
        $this->assertInstanceOf('IPLib\Range\Single', $ex, "'$str' has been recognized as a range, but not a single range");
        $this->assertSame($short, $ex->toString(false));
        $this->assertSame($long, $ex->toString(true));
    }
}
