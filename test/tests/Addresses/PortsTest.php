<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class PortsTest extends TestCase
{
    /**
     * @return array{string, bool}[]
     */
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
     *
     * @param string $address
     * @param bool $hasPort
     *
     * @return void
     */
    public function testValidAddresses($address, $hasPort)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNotNull($ip, "'{$address}' has been detected as an invalid IP, but it should be valid");
        if ($hasPort) {
            $ip = Factory::addressFromString($address, false);
            $this->assertNull($ip, "'{$address}' has a port, but we disabled parsing addresses with ports");
        }
    }

    /**
     * @return array{string}[]
     */
    public function invalidAddresses()
    {
        return array(
            array('127.0.0.1::80'),
            array('[127.0.0.1]:80'),
            array('[::1]'),
            array('[::1]:a'),
        );
    }

    /**
     * @dataProvider invalidAddresses
     *
     * @param string $address
     *
     * @return void
     */
    public function testInvalidAddresses($address)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNull($ip, "'{$address}' has been detected as valid IP, but it should be NOT valid");
    }
}
