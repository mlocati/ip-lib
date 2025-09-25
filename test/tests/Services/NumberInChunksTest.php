<?php

namespace IPLib\Test\Services;

use InvalidArgumentException;
use IPLib\Service\BinaryMath;
use IPLib\Service\NumberInChunks;
use IPLib\Test\TestCase;
use RuntimeException;

class NumberInChunksTest extends TestCase
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
     * @dataProvider provideStringConversionCases
     *
     * @param numeric-string $input
     * @param int[] $expectedBytes
     * @param int[] $expectedWords
     *
     * @return void
     */
    public function testStringConversion($input, array $expectedBytes, array $expectedWords)
    {
        if ($input !== self::$math->normalizeIntegerString($input)) {
            throw new RuntimeException('Wrong input! testStringConversion() accepts only strings normalized with normalizeIntegerString()');
        }
        if ($input[0] === '-') {
            throw new RuntimeException("Wrong input! testStringConversion() accepts only non negative numbers (we'll tests negative numbers here");
        }
        $signs = $input === '0' ? array(false) : array(false, true);
        foreach ($signs as $negative) {
            $newInput = ($negative ? '-' : '') . $input;
            $actual = NumberInChunks::fromNumericString($newInput, NumberInChunks::CHUNKSIZE_BYTES);
            $this->assertSame($negative, $actual->negative, 'NumberInBytes::negative');
            $this->assertSame($expectedBytes, $actual->chunks, 'NumberInBytes::bytes');
            $actual = NumberInChunks::fromNumericString($newInput, NumberInChunks::CHUNKSIZE_WORDS);
            $this->assertSame($negative, $actual->negative, 'NumberInWords::negative');
            $this->assertSame($expectedWords, $actual->chunks, 'NumberInWords::words');
        }
    }

    /**
     * @return array{numeric-string, int[], int[]}[]
     */
    public function provideStringConversionCases()
    {
        return array(
            array(
                '0',
                array(0),
                array(0),
            ),
            array(
                '1',
                array(1),
                array(1),
            ),
            array(
                '255',
                array(0xff),
                array(0xff),
            ),
            array(
                '256',
                array(0x1, 0x00),
                array(0x100),
            ),
            array(
                '12345',
                array(0x30, 0x39),
                array(0x3039),
            ),
            array(
                '9223372036854775807',
                array(0x7F, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF),
                array(0x7FFF, 0xFFFF, 0xFFFF, 0xFFFF),
            ),
            array(
                '12345678987654321123456789',
                array(0xA, 0x36, 0x4C, 0x99, 0x55, 0x84, 0xF9, 0x2F, 0xE9, 0x77, 0x15),
                array(0xA, 0x364C, 0x9955, 0x84F9, 0x2FE9, 0x7715),
            ),
            array(
                '12345678987654321123456790',
                array(0xA, 0x36, 0x4C, 0x99, 0x55, 0x84, 0xF9, 0x2F, 0xE9, 0x77, 0x16),
                array(0xA, 0x364C, 0x9955, 0x84F9, 0x2FE9, 0x7716),
            ),
        );
    }

    /**
     * @dataProvider provideIntegerConversionCases
     *
     * @param int|mixed $input
     * @param int[] $expectedBytes
     * @param int[] $expectedWords
     *
     * @return void
     */
    public function testIntegerConversion($input, array $expectedBytes, array $expectedWords)
    {
        if (!is_int($input)) {
            throw new RuntimeException('Wrong input! testIntegerConversion() accepts only integers');
        }
        $actual = NumberInChunks::fromInteger($input, NumberInChunks::CHUNKSIZE_BYTES);
        $this->assertSame($input < 0, $actual->negative, 'NumberInBytes::negative');
        $this->assertSame($expectedBytes, $actual->chunks, 'NumberInBytes::bytes');
        $actual = NumberInChunks::fromInteger($input, NumberInChunks::CHUNKSIZE_WORDS);
        $this->assertSame($input < 0, $actual->negative, 'NumberInWords::negative');
        $this->assertSame($expectedWords, $actual->chunks, 'NumberInWords::words');
    }

    /**
     * @return array{int, int[], int[]}[]
     */
    public function provideIntegerConversionCases()
    {
        $baseCases = array(
            array(
                0,
                array(0),
                array(0),
            ),
            array(
                1,
                array(1),
                array(1),
            ),
            array(
                255,
                array(0xff),
                array(0xff),
            ),
            array(
                256,
                array(0x1, 0x00),
                array(0x100),
            ),
            array(
                12345,
                array(0x30, 0x39),
                array(0x3039),
            ),
            array(
                0x7FFFFFFE,
                array(0x7F, 0xFF, 0xFF, 0xFE),
                array(0x7FFF, 0xFFFE),
            ),
        );
        if (PHP_INT_SIZE > 4) {
            $baseCases = array_merge($baseCases, array(
                array(
                    2147483648,
                    array(0x80, 0, 0, 0),
                    array(0x8000, 0),
                ),
                array(
                    -2147483648,
                    array(0x80, 0, 0, 0),
                    array(0x8000, 0),
                ),
                array(
                    0x7FFFFFFF,
                    array(0x7F, 0xFF, 0xFF, 0xFF),
                    array(0x7FFF, 0xFFFF),
                ),
            ));
        }
        if (PHP_INT_SIZE >= 8) {
            $baseCases = array_merge($baseCases, array(
                array(
                    9223372036854775807,
                    array(0x7F, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF),
                    array(0x7FFF, 0xFFFF, 0xFFFF, 0xFFFF),
                ),
            ));
        }

        $result = array();
        foreach ($baseCases as $baseCase) {
            $result[] = $baseCase;
            if ($baseCase[0] > 0) {
                $baseCase[0] = -$baseCase[0];
                $result[] = $baseCase;
            }
        }

        return $result;
    }

    /**
     * @dataProvider provideAddCases
     *
     * @param int|numeric-string $inputA
     * @param int|numeric-string $inputB
     * @param bool $expectedNegative
     * @param int[] $expectedBytes
     * @param int[] $expectedWords
     *
     * @return void
     */
    public function testAdd($inputA, $inputB, $expectedNegative, $expectedBytes, $expectedWords)
    {
        foreach (array(NumberInChunks::CHUNKSIZE_BYTES, NumberInChunks::CHUNKSIZE_WORDS) as $chunkSize) {
            if (is_int($inputA)) {
                $a = NumberInChunks::fromInteger($inputA, $chunkSize);
            } elseif ($inputA === self::$math->normalizeIntegerString($inputA)) {
                $a = NumberInChunks::fromNumericString($inputA, $chunkSize);
            } else {
                throw new RuntimeException('Wrong input! testAdd() accepts only integers or normalized strings');
            }
            if (is_int($inputB)) {
                $b = NumberInChunks::fromInteger($inputB, $chunkSize);
            } elseif ($inputB === self::$math->normalizeIntegerString($inputB)) {
                $b = NumberInChunks::fromNumericString($inputB, $chunkSize);
            } else {
                throw new RuntimeException('Wrong input! testAdd() accepts only integers or normalized strings');
            }
            $sum = $a->add($b);
            $this->assertSame($expectedNegative, $sum->negative, 'Wrong negative result');
            if ($chunkSize === NumberInChunks::CHUNKSIZE_BYTES) {
                $this->assertSame($expectedBytes, $sum->chunks, 'Wrong result bytes');
            } elseif ($chunkSize === NumberInChunks::CHUNKSIZE_WORDS) {
                $this->assertSame($expectedWords, $sum->chunks, 'Wrong result words');
            }
            $sum2 = $b->add($a);
            $this->assertEquals($sum, $sum2);
            $sum3 = $a->negate()->add($b->negate())->negate();
            $this->assertEquals($sum, $sum3);
        }
    }

    /**
     * @return array{int|numeric-string, int|numeric-string, bool, int[], int[]}[]
     */
    public function provideAddCases()
    {
        return array(
            array(0, 0, false, array(0), array(0)),
            array(1, 0, false, array(1), array(1)),
            array(0, 1, false, array(1), array(1)),
            array(-1, 0, true, array(1), array(1)),
            array(0, -1, true, array(1), array(1)),
            array(1, -1, false, array(0), array(0)),
            array(-1, -1, true, array(2), array(2)),
            array(255, -1, false, array(254), array(254)),
            array(255, 1, false, array(1, 0), array(256)),
            array(0xFFFF, -1, false, array(0xFF, 0xFE), array(0xFFFE)),
            array(0xFFFF, 1, false, array(1, 0, 0), array(1, 0)),
            array(0x6FFFFFFF, -0xFFFFFFF, false, array(0x60, 0, 0, 0), array(0x6000, 0)),
            array('12345678987654321123456789', '-12345678987654321123000000', false, array(0x6, 0xF8, 0x55), array(0x6, 0xF855)),
        );
    }

    /**
     * @return void
     */
    public function testAddInvalid()
    {
        $a = NumberInChunks::fromInteger(1, NumberInChunks::CHUNKSIZE_BYTES);
        $b = NumberInChunks::fromInteger(2, NumberInChunks::CHUNKSIZE_WORDS);
        $exception = null;
        try {
            $a->add($b);
        } catch (InvalidArgumentException $x) {
            $exception = $x;
        }
        $this->assertNotNull($exception, 'Adding incompatible types should throw an InvalidArgumentException');
    }
}
