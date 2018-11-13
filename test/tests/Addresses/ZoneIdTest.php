<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class ZoneIdTest extends TestCase
{
    public function validAddresses()
    {
        return array(
            array('fe80::e5da:9a3f:5b36:2ba9', false),
            array('fe80::e5da:9a3f:5b36:2ba9%7', true),
        );
    }

    /**
     * @dataProvider validAddresses
     */
    public function testValidAddresses($address, $hasZoneId)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNotNull($ip, "'$address' has been detected as an invalid IP, but it should be valid");
        if ($hasZoneId) {
            $ip = Factory::addressFromString($address, true, false);
            $this->assertNull($ip, "'$address' has a zone ID, but we disabled parsing addresses with zone ids");
        }
    }

    public function invalidAddresses()
    {
        return array(
            array('fe80::e5da:9a3x:5b36:2ba9%7'),
            array('fe80::e5da:9a3f:5b36:2ba9 %7'),
            array('fe80::e5da:e5da:e5da:e5da:e5da:e5da:9a3f:5b36:2ba9%7'),
        );
    }

    /**
     * @dataProvider invalidAddresses
     */
    public function testInvalidAddresses($address)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNull($ip, "'$address' has been detected as valid IP, but it should be NOT valid");
    }
}
