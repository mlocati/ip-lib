<?php

namespace IPLib\Test\Services;

use IPLib\Test\Helpers\UnsignedIntegerMathTestWrapper;
use IPLib\Test\TestCase;

class UnsignedIntegerMathTest extends TestCase
{
    /**
     * @var \IPLib\Test\Helpers\UnsignedIntegerMathTestWrapper
     */
    private static $math;

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Test\TestCaseBase::doSetUpBeforeClass()
     */
    protected static function doSetUpBeforeClass()
    {
        self::$math = new UnsignedIntegerMathTestWrapper();
    }

    /**
     * @return array
     */
    public function provideCases()
    {
        $calculated = array();
        for ($num = 0; $num <= 0xFF; $num++) {
            $calculated[] = array((string) $num, 1, array($num));
            $calculated[] = array('0' . decoct($num), 1, array($num));
            $calculated[] = array('0x' . dechex($num), 1, array($num));
            $num2 = ($num << 8) + 10;
            $calculated[] = array((string) $num2, 3, array(0, $num, 10));
            $calculated[] = array('0' . decoct($num2), 3, array(0, $num, 10));
            $calculated[] = array('0x' . dechex($num2), 3, array(0, $num, 10));
        }

        return array_merge($calculated, array(
            array('00000000000000000000000000000000000000000000000000000000000000000000', 1, array(0)),
            array('0x00000000000000000000000000000000000000000000000000000000000000000000', 1, array(0)),
            array('00x0', 1, null),
            array('0x1FF', 1, null),
            array('0x1FF', 2, array(1, 0xFF)),
            array('0x1FF', 3, array(0, 1, 0xFF)),
            array('0x1FF', 4, array(0, 0, 1, 0xFF)),
            array('0777', 1, null),
            array('0777', 2, array(1, 0xFF)),
            array('0777', 3, array(0, 1, 0xFF)),
            array('0777', 4, array(0, 0, 1, 0xFF)),
            array('511', 1, null),
            array('511', 2, array(1, 0xFF)),
            array('511', 3, array(0, 1, 0xFF)),
            array('511', 4, array(0, 0, 1, 0xFF)),
            array('0xFFFFFFFF', 4, array(0xFF, 0xFF, 0xFF, 0xFF)),
            array('0x000FFFFFFFF', 4, array(0xFF, 0xFF, 0xFF, 0xFF)),
            array('4294967295', 4, array(0xFF, 0xFF, 0xFF, 0xFF)),
            array('18446744073709551615', 1, null),
            array('18446744073709551615', 8, array(0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF)),
            array('18446744073709551616', 8, null),
            array('037777777777', 4, array(0xFF, 0xFF, 0xFF, 0xFF)),
            array('00000000000000000000000000000000000000000000000000000000000000000000037777777777', 4, array(0xFF, 0xFF, 0xFF, 0xFF)),
            array('0xFFFFFFFF', 9, array(0, 0, 0, 0, 0, 0xFF, 0xFF, 0xFF, 0xFF)),
            array('0', 1, array(0), true),
            array('077', 1, array(77), true),
            array('000000000000000000000000000000000000000000000000000000000000000000000077', 1, array(77), true),
            array('0x0', 1, null, true),
        ));
    }

    /**
     * @dataProvider provideCases
     *
     * @param string $value
     * @param int $numBytes
     * @param int[]|null $expectedResult
     * @param bool $onlyDecimal
     */
    public function testGetBytes($value, $numBytes, $expectedResult = null, $onlyDecimal = false)
    {
        $maxSignedIntegers = PHP_INT_SIZE === 4 ? array(null) : array(null, 0x7FFFFFFF);
        foreach ($maxSignedIntegers as $maxSignedInteger) {
            self::$math->setMaxSignedInt($maxSignedInteger);
            $actualResult = self::$math->getBytes($value, $numBytes, $onlyDecimal);
            $this->assertSame($expectedResult, $actualResult);
            $actualResult = self::$math->getBytes(strtolower($value), $numBytes, $onlyDecimal);
            $this->assertSame($expectedResult, $actualResult);
            $actualResult = self::$math->getBytes(strtoupper($value), $numBytes, $onlyDecimal);
            $this->assertSame($expectedResult, $actualResult);
        }
    }
}
