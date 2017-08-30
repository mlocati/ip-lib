<?php

namespace IPLib\Test\Addresses;

use IPLib\Factory;

class PreviousNextTest extends \PHPUnit_Framework_TestCase
{
    public function previousNextProvider()
    {
        return array(
            array('0.0.0.1', '0.0.0.0', '0.0.0.2'),
            array('0.0.0.0', '', '0.0.0.1'),
            array('1.2.3.4', '1.2.3.3', '1.2.3.5'),
            array('1.2.3.0', '1.2.2.255', '1.2.3.1'),
            array('1.2.3.255', '1.2.3.254', '1.2.4.0'),
            array('1.0.0.0', '0.255.255.255', '1.0.0.1'),
            array('1.255.255.0', '1.255.254.255', '1.255.255.1'),
            array('1.255.255.255', '1.255.255.254', '2.0.0.0'),
            array('255.255.255.255', '255.255.255.254', ''),
            array('::1', '::', '::2'),
            array('::', '', '::1'),
            array('1:2:3:4:5:6:7:8', '1:2:3:4:5:6:7:7', '1:2:3:4:5:6:7:9'),
            array('::ffff', '::fffe', '::1:0'),
            array('::ffff:ffff:ffff:0', '::ffff:ffff:fffe:ffff', '::ffff:ffff:ffff:1'),
            array('::ffff:ffff:ffff:ffff', '::ffff:ffff:ffff:fffe', '0:0:0:1::'),
            array('0:ffff:ffff:ffff:ffff:ffff:ffff:ffff', '0:ffff:ffff:ffff:ffff:ffff:ffff:fffe', '1::'),
            array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe', ''),
        );
    }

    /**
     * @dataProvider previousNextProvider
     */
    public function testPreviousNext($addressString, $previousString, $nextString)
    {
        $address = Factory::addressFromString($addressString);
        $this->assertInstanceof('IPLib\Address\AddressInterface', $address, "Checking that {$addressString} is a valid address");
        $previous = $address->getPreviousAddress();
        $this->assertSame($previousString, (string) $previous, "Checking the address before {$addressString}");
        if ($previous !== null) {
            $this->assertSame($addressString, (string) $previous->getNextAddress(), "Checking the address after the address before {$addressString}");
        }
        $next = $address->getNextAddress();
        $this->assertSame($nextString, (string) $next, "Checking the address after {$addressString}");
        if ($next !== null) {
            $this->assertSame($addressString, (string) $next->getPreviousAddress(), "Checking the address before the address after {$addressString}");
        }
    }
}
