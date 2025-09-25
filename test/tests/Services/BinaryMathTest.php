<?php

namespace IPLib\Test\Services;

use IPLib\Service\BinaryMath;
use IPLib\Test\TestCase;
use ReflectionProperty;
use RuntimeException;

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

    /**
     * @return void
     */
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
     *
     * @return void
     */
    public function testReduce($value, $expectedResult)
    {
        $result = self::$math->reduce($value);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array{string, string}[]
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
     * @param int $expectedResult
     *
     * @return void
     */
    public function testCompare($a, $b, $expectedResult)
    {
        $result = self::$math->compare($a, $b);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array{string, string, int}[]
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
     *
     * @return void
     */
    public function testIncrement($value, $expectedResult)
    {
        $result = self::$math->increment($value);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array{string, string}[]
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
     *
     * @return void
     */
    public function testAnd($operand1, $operand2, $expectedResult)
    {
        $result = self::$math->andX($operand1, $operand2);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array{string, string, string}[]
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
     *
     * @return void
     */
    public function testOr($operand1, $operand2, $expectedResult)
    {
        $result = self::$math->orX($operand1, $operand2);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array{string, string, string}[]
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

    /**
     * @dataProvider providePow2stringCases
     *
     * @param int $exponent
     * @param int|string $expectedResult
     *
     * @return void
     */
    public function testPow2string($exponent, $expectedResult)
    {
        $actualResult = self::$math->pow2string($exponent);

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array{int, int|string}[]
     */
    public function providePow2stringCases()
    {
        return array(
            array(0, 1),
            array(1, 2),
            array(2, 4),
            array(3, 8),
            array(30, 0x40000000),
            array(31, PHP_INT_SIZE > 4 ? 0x80000000 : '2147483648'),
            array(32, PHP_INT_SIZE > 4 ? 0x100000000 : '4294967296'),
            array(33, PHP_INT_SIZE > 4 ? 0x200000000 : '8589934592'),
            array(62, PHP_INT_SIZE > 4 ? 0x4000000000000000 : '4611686018427387904'),
            // @phpstan-ignore greater.alwaysFalse
            array(63, PHP_INT_SIZE > 8 ? 0x8000000000000000 : '9223372036854775808'),
            // @phpstan-ignore greater.alwaysFalse
            array(64, PHP_INT_SIZE > 8 ? 0x10000000000000000 : '18446744073709551616'),
            // @phpstan-ignore greater.alwaysFalse
            array(65, PHP_INT_SIZE > 8 ? 0x20000000000000000 : '36893488147419103232'),
            // @phpstan-ignore greater.alwaysFalse
            array(126, PHP_INT_SIZE > 8 ? 0x20000000000000000000000000000000 : '85070591730234615865843651857942052864'),
            // @phpstan-ignore greater.alwaysFalse
            array(127, PHP_INT_SIZE > 9 ? 0x40000000000000000000000000000000 : '170141183460469231731687303715884105728'),
            // @phpstan-ignore greater.alwaysFalse
            array(128, PHP_INT_SIZE > 9 ? 0x80000000000000000000000000000000 : '340282366920938463463374607431768211456'),
            // @phpstan-ignore greater.alwaysFalse
            array(129, PHP_INT_SIZE > 9 ? 0x100000000000000000000000000000000 : '680564733841876926926749214863536422912'),
        );
    }

    /**
     * @dataProvider provideNormalizeIntegerStringCases
     *
     * @param mixed $input
     * @param string $expectedResult
     *
     * @return void
     */
    public function testNormalizeIntegerString($input, $expectedResult = '')
    {
        $actualResult = self::$math->normalizeIntegerString($input);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array{mixed, 1?: string}[]
     */
    public function provideNormalizeIntegerStringCases()
    {
        return array(
            array(new \stdClass()),
            array(false),
            array(array()),
            array(null),
            array(''),
            array('+'),
            array('-'),
            array('0.0'),
            array('.0'),
            array('0.'),
            array('123a'),
            array('a123'),
            array('123 '),
            array(' 123'),
            array('0', '0'),
            array('1', '1'),
            array('00', '0'),
            array('0000001', '1'),
            array('-0', '0'),
            array('-1', '-1'),
            array('-00001', '-1'),
            array('-0000100', '-100'),
            array(str_repeat('0', 100), '0'),
            array('098765432100', '98765432100'),
            array('-0009876543210098765432100987654321009876543210098765432100987654321009876543210098765432100', '-9876543210098765432100987654321009876543210098765432100987654321009876543210098765432100'),
        );
    }

    /**
     * @dataProvider provideAdd1ToIntegerStringCases
     *
     * @param numeric-string $input
     * @param numeric-string $expectedResult
     *
     * @return void
     */
    public function testAdd1ToIntegerString($input, $expectedResult)
    {
        if ($input !== self::$math->normalizeIntegerString($input)) {
            throw new RuntimeException('Wrong input! add1ToIntegerString() accepts only strings normalized with normalizeIntegerString()');
        }
        $actualResult = self::$math->add1ToIntegerString($input);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array{numeric-string, numeric-string}[]
     */
    public function provideAdd1ToIntegerStringCases()
    {
        return array(
            array('0', '1'),
            array('1', '2'),
            array('99999999999999999999999999999999999999999999', '100000000000000000000000000000000000000000000'),
            array('100000000000000000000000000000000000000000000', '100000000000000000000000000000000000000000001'),
            array('100000000000000000000000000000000000000000001', '100000000000000000000000000000000000000000002'),
            array('-1', '0'),
            array('-100000000000000000000000000000000000000000001', '-100000000000000000000000000000000000000000000'),
            array('-100000000000000000000000000000000000000000000', '-99999999999999999999999999999999999999999999'),
            array('-99999999999999999999999999999999999999999999', '-99999999999999999999999999999999999999999998'),
        );
    }
}
