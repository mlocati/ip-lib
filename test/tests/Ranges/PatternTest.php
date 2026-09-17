<?php

namespace IPLib\Test\Ranges;

use IPLib\Factory;
use IPLib\Range\Pattern;
use IPLib\Test\TestCase;

class PatternTest extends TestCase
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
            array('*'),
            array('0.0.0.0'),
            array('127.0.0.1'),
            array('10.20.30.40'),
            array('255.255.255.255'),
            array('255.255.255.**'),
            array('*::*'),
            array("127.0.0.*\n"),
            array("::*\n"),
            array(':*::'),
            // Valid pattern syntax, but invalid address
            array('1.2.3.256.*'),
            array('1:2:3:4:5:6:7:8:*'),
            array('zz::*'),
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
        $this->assertNull(Pattern::fromString($range), json_encode($range) . " has been recognized as a pattern range, but it shouldn't");
    }

    /**
     * @return array{string, string, string}[]
     */
    public function validProvider()
    {
        return array(
            array('0.0.0.*', '0.0.0.*', '0.0.0.*'),
            array('127.0.0.*', '127.0.0.*', '127.0.0.*'),
            array('127.1.*', '127.1.*.*', '127.1.*.*'),
            array('127.*', '127.*.*.*', '127.*.*.*'),
            array('255.255.255.*', '255.255.255.*', '255.255.255.*'),
            array('0.0.*.*', '0.0.*.*', '0.0.*.*'),
            array('127.0.*.*', '127.0.*.*', '127.0.*.*'),
            array('255.255.*.*', '255.255.*.*', '255.255.*.*'),
            array('0.*.*.*', '0.*.*.*', '0.*.*.*'),
            array('127.*.*.*', '127.*.*.*', '127.*.*.*'),
            array('255.*.*.*', '255.*.*.*', '255.*.*.*'),
            array('*.*.*.*', '*.*.*.*', '*.*.*.*'),
            array('::*', '::*', '0000:0000:0000:0000:0000:0000:0000:*'),
            array('0:0::*', '::*', '0000:0000:0000:0000:0000:0000:0000:*'),
            array('1::*', '1::*', '0001:0000:0000:0000:0000:0000:0000:*'),
            array('::1:*', '::1:*', '0000:0000:0000:0000:0000:0000:0001:*'),
            array('::*:*', '::*:*', '0000:0000:0000:0000:0000:0000:*:*'),
            array('::ffff:*:*', '::ffff:*:*', '0000:0000:0000:0000:0000:ffff:*:*'),
            array('::ffff:0:*', '::ffff:0:*', '0000:0000:0000:0000:0000:ffff:0000:*'),
            array('::ffff:102:*', '::ffff:102:*', '0000:0000:0000:0000:0000:ffff:0102:*'),
            array('1:0:0:2:0:0:0:*', '1:0:0:2::*', '0001:0000:0000:0002:0000:0000:0000:*'),
            array('*:*:*:*:*:*:*:*', '*:*:*:*:*:*:*:*', '*:*:*:*:*:*:*:*'),
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
        $this->assertInstanceOf('IPLib\Range\Pattern', $ex, "'{$range}' has been recognized as a range, but not a Pattern range");
        $this->assertSame($short, $ex->toString(false));
        $this->assertSame($long, $ex->toString(true));
        $reparsed = Factory::parseRangeString($short);
        $this->assertInstanceOf('IPLib\Range\Pattern', $reparsed, "'{$short}' has not been recognized as a Pattern range, but it should");
        $this->assertSame($short, $reparsed->toString(false));
    }
}
