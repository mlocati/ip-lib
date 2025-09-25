<?php

namespace IPLib\Test;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCaseBase extends PHPUnitTestCase
{
    /**
     * This method is called before the first test of this test class is run.
     * Override it instead of setUpBeforeClass().
     *
     * @return void
     */
    protected static function doSetUpBeforeClass()
    {
    }

    /**
     * This method is called before each test.
     * Override it instead of setUp().
     *
     * @return void
     */
    protected function doSetUp()
    {
    }
}
