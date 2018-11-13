<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class PortsTest extends TestCase
{
    public function validAddresses()
    {
        return array(
            array('127.0.0.1', false),
            array('127.0.0.1:80', true),
            array('::1', false),
            array('[::1]:80', true),
        );
    }

    /**
     * @dataProvider validAddresses
     */
    public function testValidAddresses($address, $hasPort)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNotNull($ip, "'$address' has been detected as an invalid IP, but it should be valid");
        if ($hasPort) {
            $ip = Factory::addressFromString($address, false);
            $this->assertNull($ip, "'$address' has a port, but we disabled parsing addresses with ports");
        }
    }

    public function invalidAddresses()
    {
        return array(
            array('127.0.0.1::80', false),
            array('[127.0.0.1]:80', true),
            array('[::1]', false),
            array('[::1]:a', true),
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
