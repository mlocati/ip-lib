<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;
use IPLib\Test\TestCase;

class GetNumberOfBitsTest extends TestCase
{
    /**
     * @return array{string, int}[]
     */
    public function provideTestCases()
    {
        return array(
            array('0.0.0.0', 32),
            array('::', 128),
        );
    }

    /**
     * @dataProvider provideTestCases
     *
     * @param string $address
     * @param int $expectedNumberOfBits
     *
     * @return void
     */
    public function testGetBits($address, $expectedNumberOfBits)
    {
        $ip = Factory::addressFromString($address);
        $this->assertNotNull($ip, "'{$address}' has been detected as an invalid IP, but it should be valid");
        $this->assertSame($expectedNumberOfBits, $ip->getNumberOfBits());
        $this->assertSame($expectedNumberOfBits, strlen($ip->getBits()));
    }
}
