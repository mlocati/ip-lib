<?php

namespace IPLib\Test;

abstract class TestCase4 extends TestCaseBase
{
    /**
     * @return void
     */
    public static function setupBeforeClass()
    {
        static::doSetUpBeforeClass();
    }

    /**
     * @return void
     */
    public function setUp()
    {
        static::doSetUp();
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     *
     * @return void
     */
    public static function assertStringNotContainsString($needle, $haystack, $message = '')
    {
        static::assertNotContains($needle, $haystack, $message);
    }

    /**
     * @param string $pattern
     * @param string $string
     * @param string $message
     *
     * @return void
     */
    public static function assertMatchRegExp($pattern, $string, $message = '')
    {
        static::assertRegExp($pattern, $string, $message);
    }
}
