<?php

namespace IPLib\Test\Services;

use IPLib\Service\BinaryMath;
use IPLib\Test\TestCase;
use ReflectionProperty;

class BinaryMathTest extends TestCase
{
    /**
     * @var \IPLib\Service\BinaryMath
     */
    private static $math;

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Test\TestCaseBase::doSetUpBeforeClass()
     */
    protected static function doSetUpBeforeClass()
    {
        self::$math = BinaryMath::getInstance();
    }

    public function testSingleton()
    {
        $this->assertSame(self::$math, BinaryMath::getInstance());
        $instanceProperty = new ReflectionProperty('IPLib\\Service\\BinaryMath', 'instance');
        if (PHP_VERSION_ID < 80100) {
            $instanceProperty->setAccessible(true);
        }
        $instanceProperty->setValue(null, null);
        $newMath = BinaryMath::getInstance();
        $this->assertEquals(self::$math, $newMath);
        $this->assertNotSame(self::$math, $newMath);
    }

    /**
     * @dataProvider provideReduceCases
     *
     * @param string $value
     * @param string $expectedResult
     */
    public function testReduce($value, $expectedResult)
    {
        $result = self::$math->reduce($value);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function provideReduceCases()
    {
        return array(
            array('0', '0'),
            array('1', '1'),
            array('001', '1'),
            array('0010', '10'),
            array(str_repeat('0', 1000) . '110', '110'),
        );
    }

    /**
     * @dataProvider provideCompareCases
     *
     * @param string $a
     * @param string $b
     * @param string $expectedResult
     */
    public function testCompare($a, $b, $expectedResult)
    {
        $result = self::$math->compare($a, $b);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function provideCompareCases()
    {
        return array(
            array('0', '0', 0),
            array('1', '0', 1),
            array('0', '1', -1),
            array('000', '0', 0),
            array('001', '0', 1),
            array('000', '1', -1),
            array('0', '000', 0),
            array('1', '000', 1),
            array('0', '001', -1),
            array('000', '000', 0),
            array('001', '000', 1),
            array('000', '001', -1),
            array('10', '00', 1),
            array('10', '01', 1),
            array('10', '10', 0),
            array('10', '11', -1),
        );
    }

    /**
     * @dataProvider provideIncrementCases
     *
     * @param string $value
     * @param string $expectedResult
     */
    public function testIncrement($value, $expectedResult)
    {
        $result = self::$math->increment($value);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function provideIncrementCases()
    {
        $cases = array();
        $max = 40; // Must be less than PHP_INT_MAX - 1;
        for ($int = 0; $int <= $max; $int++) {
            $cases[] = array(decbin($int), decbin($int + 1));
            $cases[] = array('0' . decbin($int), decbin($int + 1));
            $cases[] = array('00' . decbin($int), decbin($int + 1));
        }

        return $cases;
    }

    /**
     * @dataProvider provideAndCases
     *
     * @param string $operand1
     * @param string $operand2
     * @param string $expectedResult
     */
    public function testAnd($operand1, $operand2, $expectedResult)
    {
        $result = self::$math->andX($operand1, $operand2);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function provideAndCases()
    {
        $cases = array();
        $max = 10; // Must be less than PHP_INT_MAX - 1;
        for ($operand1 = 0; $operand1 <= $max; $operand1++) {
            for ($operand2 = 0; $operand2 <= $max; $operand2++) {
                $cases[] = array(decbin($operand1), decbin($operand2), decbin($operand1 & $operand2));
                $cases[] = array('00' . decbin($operand1), decbin($operand2), decbin($operand1 & $operand2));
                $cases[] = array(decbin($operand1), '00' . decbin($operand2), decbin($operand1 & $operand2));
                $cases[] = array('00' . decbin($operand1), '00' . decbin($operand2), decbin($operand1 & $operand2));
            }
        }

        return $cases;
    }

    /**
     * @dataProvider provideOrCases
     *
     * @param string $operand1
     * @param string $operand2
     * @param string $expectedResult
     */
    public function testOr($operand1, $operand2, $expectedResult)
    {
        $result = self::$math->orX($operand1, $operand2);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function provideOrCases()
    {
        $cases = array();
        $max = 10; // Must be less than PHP_INT_MAX - 1;
        for ($operand1 = 0; $operand1 <= $max; $operand1++) {
            for ($operand2 = 0; $operand2 <= $max; $operand2++) {
                $cases[] = array(decbin($operand1), decbin($operand2), decbin($operand1 | $operand2));
                $cases[] = array('00' . decbin($operand1), decbin($operand2), decbin($operand1 | $operand2));
                $cases[] = array(decbin($operand1), '00' . decbin($operand2), decbin($operand1 | $operand2));
                $cases[] = array('00' . decbin($operand1), '00' . decbin($operand2), decbin($operand1 | $operand2));
            }
        }

        return $cases;
    }
}
