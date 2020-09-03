<?php

namespace IPLib\Test\Ranges;

use IPLib\Address\AssignedRange;
use IPLib\Factory;
use IPLib\Range\Type;
use IPLib\Test\TestCase;

class AssignedRangeTest extends TestCase
{
    /**
     * A minor function to test class getters.
     */
    public function testGetters()
    {
        $range = Factory::rangeFromString('10.0.0.0/8');
        $exceptions = array($range);

        $assignedRange = new AssignedRange($range, Type::PRIVATE_NETWORK, $exceptions);
        $this->assertEquals($range, $assignedRange->getRange());
        $this->assertEquals($exceptions, $assignedRange->getExceptions());
    }
}
