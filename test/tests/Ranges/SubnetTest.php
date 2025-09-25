<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Subnet;
use IPLib\Test\TestCase;

class SubnetTest extends TestCase
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
     *
     * @param string|mixed $range
     *
     * @return void
     */
    public function testInvalid($range)
    {
        $this->assertNull(Subnet::fromString($range), json_encode($range) . " has been recognized as a subnet range, but it shouldn't");
    }

    /**
     * @return array{string, string, string}[]
     */
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
        $this->assertInstanceOf('IPLib\Range\Subnet', $ex, "'{$range}' has been recognized as a range, but not a subnet range");
        $this->assertSame($short, $ex->toString(false));
        $this->assertSame($long, $ex->toString(true));
    }
}
