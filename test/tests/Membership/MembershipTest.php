<?php

namespace IPLib\Test\Membership;

use IPLib\Factory;

if (!class_exists('IPLib\Test\SQLiteDatabase')) {
    require_once __DIR__.'/../SQLiteDatabase.php';
}

class MembershipTest extends \IPLib\Test\SQLiteDatabase
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
     */
    public function testAddressMembership($address, $range, $contained)
    {
        $addressStr = @strval($address);
        $rangeStr = @strval($range);

        $addressObject = Factory::addressFromString($address);
        $this->assertNotNull($addressObject, "'$addressStr' has not been recognized as an address");
        $this->assertInstanceOf('IPLib\Address\AddressInterface', $addressObject);

        $rangeObject = Factory::rangeFromString($range);
        $this->assertNotNull($rangeObject, "'$rangeStr' has not been recognized as a range");
        $this->assertInstanceOf('IPLib\Range\RangeInterface', $rangeObject);

        if ($contained) {
            $this->assertSame(true, $rangeObject->contains($addressObject), "Failed to check that '$rangeStr' contains '$addressStr'");
            $this->assertSame(true, $addressObject->matches($rangeObject), "Failed to check that '$addressStr' is contained in '$rangeStr'");
        } else {
            $this->assertSame(false, $rangeObject->contains($addressObject), "Failed to check that '$rangeStr' does not contain '$addressStr'");
            $this->assertSame(false, $addressObject->matches($rangeObject), "Failed to check that '$addressStr' is not contained in '$rangeStr'");
        }
        $pdo = $this->getConnection()->getConnection();
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
            $this->assertNotEmpty($foundRow, "Failed to check that '$rangeStr' contains '$addressStr' using database comparison");
        } else {
            $this->assertFalse($foundRow, "Failed to check that '$rangeStr' does not contain '$addressStr' using database comparison");
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
     */
    public function testRangeMembership($rangeString, $otherRangeString, $contained)
    {
        $range = Factory::rangeFromString($rangeString);
        $this->assertNotNull($range, "'$rangeString' has not been recognized as a range");
        $otherRange = Factory::rangeFromString($otherRangeString);
        $this->assertNotNull($range, "'$otherRangeString' has not been recognized as a range");
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
}
