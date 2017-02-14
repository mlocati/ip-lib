<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Subnet;

class SubnetTest extends \PHPUnit_Framework_TestCase
{
    public function invalidProvider()
    {
        return array(
            array(null),
            array(false),
            array(''),
            array(array()),
            array('*/1'),
            array('0.0.0.0/1/2'),
            array('127.0.0.1/-1'),
            array('127.0.0.1/33'),
            array('::'),
            array('::/-1'),
            array('::/129'),
        );
    }

    /**
     * @dataProvider invalidProvider
     */
    public function testInvalid($range)
    {
        $str = @strval($range);
        $this->assertNull(Subnet::fromString($range), "'$str' has been recognized as a subnet range, but it shouldn't");
    }

    public function validProvider()
    {
        return array(
            array('0.0.0.0/0', '0.0.0.0/0', '000.000.000.000/0'),
            array('0.0.0.0/32', '0.0.0.0/32', '000.000.000.000/32'),
            array('::/0', '::/0', '0000:0000:0000:0000:0000:0000:0000:0000/0'),
            array('::/128', '::/128', '0000:0000:0000:0000:0000:0000:0000:0000/128'),
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
        $this->assertInstanceOf('IPLib\Range\Subnet', $ex, "'$str' has been recognized as a range, but not a subnet range");
        $this->assertSame($short, $ex->toString(false));
        $this->assertSame($long, $ex->toString(true));
    }
}
