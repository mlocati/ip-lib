<?php

namespace IPLib\Test\Membership;

use IPLib\Factory;
use IPLib\Test\DBTestCase;

class MembershipTest extends DBTestCase
{
    public function addressMembershipProvider()
    {
        return array(
            array('0.0.0.0', '0.0.0.0', true),
            array('0.0.0.1', '0.0.0.0', false),
            array('127.0.0.0', '127.0.0.*', true),
            array('127.0.0.1', '127.0.0.*', true),
            array('127.0.0.255', '127.0.0.*', true),
            array('127.0.0.127', '127.0.1.*', false),
            array('127.0.0.0', '127.0.0.0/32', true),
            array('127.0.0.0', '127.0.0.255/24', true),
            array('127.0.0.0', '127.0.1.255/24', false),
            array('127.0.0.0', '127.0.1.255/16', true),
            array('::', '::', true),
            array('::1', '::', false),
            array('::', '::1', false),
            array('::1', '::*', true),
            array('::1:1', '::*', false),
            array('::1:1', '::1:*', true),
            array('::1:1', '::*:*', true),
            array('::1:1:1', '::*:*', false),
            array('::1:1:1', '::1:*:*', true),
            array('::', 'ffff::/128', false),
            array('::', 'ffff::/1', false),
            array('::', 'ffff::/0', true),
            array('::', '0.0.0.0', false),
            array('0.0.0.0', '::', false),
        );
    }

    /**
     * @dataProvider addressMembershipProvider
     *
     * @param string $address
     * @param string $range
     * @param bool $contained
     */
    public function testAddressMembership($address, $range, $contained)
    {
        $addressObject = Factory::addressFromString($address);
        $this->assertNotNull($addressObject, "'{$address}' has not been recognized as an address");
        $this->assertInstanceOf('IPLib\Address\AddressInterface', $addressObject);

        $rangeObject = Factory::rangeFromString($range);
        $this->assertNotNull($rangeObject, "'{$range}' has not been recognized as a range");
        $this->assertInstanceOf('IPLib\Range\RangeInterface', $rangeObject);

        if ($contained) {
            $this->assertSame(true, $rangeObject->contains($addressObject), "Failed to check that '{$range}' contains '{$address}'");
            $this->assertSame(true, $addressObject->matches($rangeObject), "Failed to check that '{$address}' is contained in '{$range}'");
        } else {
            $this->assertSame(false, $rangeObject->contains($addressObject), "Failed to check that '{$range}' does not contain '{$address}'");
            $this->assertSame(false, $addressObject->matches($rangeObject), "Failed to check that '{$address}' is not contained in '{$range}'");
        }
        $pdo = $this->getConnection();
        $insertQuery = $pdo->prepare('insert into ranges (rangeRepresentation, addressType, rangeFrom, rangeTo) values (:rangeRepresentation, :addressType, :rangeFrom, :rangeTo)');
        $insertQuery->execute(array(
            ':rangeRepresentation' => $rangeObject->__toString(),
            ':addressType' => $rangeObject->getAddressType(),
            ':rangeFrom' => $rangeObject->getComparableStartString(),
            ':rangeTo' => $rangeObject->getComparableEndString(),
        ));
        $searchQuery = $pdo->prepare('select id from ranges where addressType = :addressType and :address between rangeFrom and rangeTo');
        $searchQuery->execute(array(
            ':addressType' => $addressObject->getAddressType(),
            ':address' => $addressObject->getComparableString(),
        ));
        $foundRow = $searchQuery->fetch();
        $searchQuery->closeCursor();
        if ($contained) {
            $this->assertNotEmpty($foundRow, "Failed to check that '{$range}' contains '{$address}' using database comparison");
        } else {
            $this->assertFalse($foundRow, "Failed to check that '{$range}' does not contain '{$address}' using database comparison");
        }
    }

    public function rangeMembershipProvider()
    {
        return array(
            array('0.0.0.0', '0.0.0.0', true),
            array('0.0.0.*', '0.0.0.0', true),
            array('0.0.0.0', '0.0.0.*', false),
            array('0.0.0.127', '0.0.0.0', false),
            array('0.0.0.127', '0.0.0.127', true),
            array('0.0.0.127', '0.0.0.255', false),
            array('0::/32', '0::/32', true),
            array('0::/32', '0::/64', true),
            array('0::/32', '0::/16', false),
            array('0:0:*:*:*:*:*:*', '0:0:*:*:*:*:*:*', true),
            array('0:0:*:*:*:*:*:*', '0:0:0:*:*:*:*:*', true),
            array('0:0:*:*:*:*:*:*', '0:*:*:*:*:*:*:*', false),
        );
    }

    /**
     * @dataProvider rangeMembershipProvider
     *
     * @param string $rangeString
     * @param string $otherRangeString
     * @param bool $contained
     */
    public function testRangeMembership($rangeString, $otherRangeString, $contained)
    {
        $range = Factory::rangeFromString($rangeString);
        $this->assertNotNull($range, "'{$rangeString}' has not been recognized as a range");
        $otherRange = Factory::rangeFromString($otherRangeString);
        $this->assertNotNull($range, "'{$otherRangeString}' has not been recognized as a range");
        $this->assertSame(
            $contained,
            $range->containsRange($otherRange),
            sprintf(
                $contained ? '%1$s should contain %2$s' : '%1$s should not contain %2$s',
                $rangeString,
                $otherRangeString
            )
        );
    }

    public function sameRangeProvider()
    {
        return array(
            array('1.2.3.4', '1.2.3.4/32'),
        );
    }

    /**
     * @dataProvider sameRangeProvider
     *
     * @param string $range1
     * @param string $range2
     */
    public function testSameRange($range1, $range2)
    {
        $rangeObject1 = Factory::rangeFromString($range1);
        $rangeObject2 = Factory::rangeFromString($range2);
        $this->assertTrue($rangeObject1->containsRange($rangeObject1), "{$range1} should contain {$range1}");
        $this->assertTrue($rangeObject2->containsRange($rangeObject2), "{$range2} should contain {$range2}");
        $this->assertTrue($rangeObject1->containsRange($rangeObject2), "{$range1} should contain {$range2}");
        $this->assertTrue($rangeObject2->containsRange($rangeObject1), "{$range2} should contain {$range1}");
    }
}
