<?php

namespace IPLib\Test\Addresses;

use IPLib\Address\IPv4;
use IPLib\Factory;
use IPLib\Test\TestCase;

class NonDecimalIPv4AddressTest extends TestCase
{
    const OCT_SHORT = 'os';

    const OCT_LONG = 'ol';

    const DEC_SHORT = 'ds';

    const DEC_LONG = 'dl';

    const HEX_SHORT = 'xs';

    const HEX_LONG = 'xl';

    public function casesProvider()
    {
        return array(
            array(
                '0.0.0.377',
                false,
            ),
            array(
                '0.0.0.377',
                true,
            ),
            array(
                '0.0.0.0377',
                false,
            ),
            array(
                '0.0.0.0377',
                true,
                array(
                    self::OCT_SHORT => '00.00.00.0377',
                    self::OCT_LONG => '0000.0000.0000.0377',
                    self::DEC_SHORT => '0.0.0.255',
                    self::DEC_LONG => '000.000.000.255',
                    self::HEX_SHORT => '0x0.0x0.0x0.0xff',
                    self::HEX_LONG => '0x00.0x00.0x00.0xff',
                ),
            ),
            array(
                '0.0.0.0000000000000000000000000000377',
                false,
            ),
            array(
                '0.0.0.0000000000000000000000000000377',
                true,
                array(
                    self::OCT_SHORT => '00.00.00.0377',
                    self::OCT_LONG => '0000.0000.0000.0377',
                    self::DEC_SHORT => '0.0.0.255',
                    self::DEC_LONG => '000.000.000.255',
                    self::HEX_SHORT => '0x0.0x0.0x0.0xff',
                    self::HEX_LONG => '0x00.0x00.0x00.0xff',
                ),
            ),
            array(
                '000000000.00000000000.0000000.00000000000000',
                false,
                array(
                    self::OCT_SHORT => '00.00.00.00',
                    self::OCT_LONG => '0000.0000.0000.0000',
                    self::DEC_SHORT => '0.0.0.0',
                    self::DEC_LONG => '000.000.000.000',
                    self::HEX_SHORT => '0x0.0x0.0x0.0x0',
                    self::HEX_LONG => '0x00.0x00.0x00.0x00',
                ),
            ),
            array(
                '000000000.00000000000.0000000.00000000000000',
                true,
                array(
                    self::OCT_SHORT => '00.00.00.00',
                    self::OCT_LONG => '0000.0000.0000.0000',
                    self::DEC_SHORT => '0.0.0.0',
                    self::DEC_LONG => '000.000.000.000',
                    self::HEX_SHORT => '0x0.0x0.0x0.0x0',
                    self::HEX_LONG => '0x00.0x00.0x00.0x00',
                ),
            ),
            array(
                '10.20.30.40',
                false,
                array(
                    self::OCT_SHORT => '012.024.036.050',
                    self::OCT_LONG => '0012.0024.0036.0050',
                    self::DEC_SHORT => '10.20.30.40',
                    self::DEC_LONG => '010.020.030.040',
                    self::HEX_SHORT => '0xa.0x14.0x1e.0x28',
                    self::HEX_LONG => '0x0a.0x14.0x1e.0x28',
                ),
            ),
            array(
                '10.20.30.40',
                true,
                array(
                    self::OCT_SHORT => '012.024.036.050',
                    self::OCT_LONG => '0012.0024.0036.0050',
                    self::DEC_SHORT => '10.20.30.40',
                    self::DEC_LONG => '010.020.030.040',
                    self::HEX_SHORT => '0xa.0x14.0x1e.0x28',
                    self::HEX_LONG => '0x0a.0x14.0x1e.0x28',
                ),
            ),
            array(
                '010.020.030.040',
                false,
                array(
                    self::OCT_SHORT => '012.024.036.050',
                    self::OCT_LONG => '0012.0024.0036.0050',
                    self::DEC_SHORT => '10.20.30.40',
                    self::DEC_LONG => '010.020.030.040',
                    self::HEX_SHORT => '0xa.0x14.0x1e.0x28',
                    self::HEX_LONG => '0x0a.0x14.0x1e.0x28',
                ),
            ),
            array(
                '010.020.030.040',
                true,
                array(
                    self::OCT_SHORT => '010.020.030.040',
                    self::OCT_LONG => '0010.0020.0030.0040',
                    self::DEC_SHORT => '8.16.24.32',
                    self::DEC_LONG => '008.016.024.032',
                    self::HEX_SHORT => '0x8.0x10.0x18.0x20',
                    self::HEX_LONG => '0x08.0x10.0x18.0x20',
                ),
            ),
            array(
                '0000000000010.0000000000020.0000000000030.0000000000040',
                false,
                array(
                    self::OCT_SHORT => '012.024.036.050',
                    self::OCT_LONG => '0012.0024.0036.0050',
                    self::DEC_SHORT => '10.20.30.40',
                    self::DEC_LONG => '010.020.030.040',
                    self::HEX_SHORT => '0xa.0x14.0x1e.0x28',
                    self::HEX_LONG => '0x0a.0x14.0x1e.0x28',
                ),
            ),
            array(
                '0000000000010.0000000000020.0000000000030.0000000000040',
                true,
                array(
                    self::OCT_SHORT => '010.020.030.040',
                    self::OCT_LONG => '0010.0020.0030.0040',
                    self::DEC_SHORT => '8.16.24.32',
                    self::DEC_LONG => '008.016.024.032',
                    self::HEX_SHORT => '0x8.0x10.0x18.0x20',
                    self::HEX_LONG => '0x08.0x10.0x18.0x20',
                ),
            ),
            array(
                '010.020.030.0x40',
                false,
            ),
            array(
                '010.020.030.0x40',
                true,
                array(
                    self::OCT_SHORT => '010.020.030.0100',
                    self::OCT_LONG => '0010.0020.0030.0100',
                    self::DEC_SHORT => '8.16.24.64',
                    self::DEC_LONG => '008.016.024.064',
                    self::HEX_SHORT => '0x8.0x10.0x18.0x40',
                    self::HEX_LONG => '0x08.0x10.0x18.0x40',
                ),
            ),
            array(
                '010.020.030.0000000000000000000000000000000000x00000000000000000000000040',
                true,
                array(
                    self::OCT_SHORT => '010.020.030.0100',
                    self::OCT_LONG => '0010.0020.0030.0100',
                    self::DEC_SHORT => '8.16.24.64',
                    self::DEC_LONG => '008.016.024.064',
                    self::HEX_SHORT => '0x8.0x10.0x18.0x40',
                    self::HEX_LONG => '0x08.0x10.0x18.0x40',
                ),
            ),
        );
    }

    /**
     * @dataProvider casesProvider
     *
     * @param string     $input
     * @param bool       $parseNonDecimal
     * @param array|null $expected
     */
    public function testCases($input, $parseNonDecimal, array $expected = null)
    {
        /* @var $ip IPv4 */
        $ip = Factory::addressFromString($input, true, true, $parseNonDecimal);
        if ($expected === null) {
            $this->assertNull($ip);

            return;
        }
        $this->assertInstanceOf('IPLib\Address\IPv4', $ip);
        $octShort = $ip->toOctal();
        $this->assertSame($expected[self::OCT_SHORT], $octShort);
        $this->assertSame($octShort, $ip->toOctal(false));
        $this->assertEquals($expected[self::DEC_SHORT], (string) Factory::addressFromString($octShort, false, false, true));
        $octLong = $ip->toOctal(true);
        $this->assertSame($expected[self::OCT_LONG], $octLong);
        $this->assertEquals($expected[self::DEC_SHORT], (string) Factory::addressFromString($octLong, false, false, true));
        $decShort = $ip->toString();
        $this->assertSame($expected[self::DEC_SHORT], $decShort);
        $this->assertSame($decShort, $ip->toString(false));
        $this->assertEquals($expected[self::DEC_SHORT], (string) Factory::addressFromString($decShort, false, false, false));
        $this->assertEquals($expected[self::DEC_SHORT], (string) Factory::addressFromString($decShort, false, false, true));
        $decLong = $ip->toString(true);
        $this->assertSame($expected[self::DEC_LONG], $decLong);
        $this->assertEquals($expected[self::DEC_SHORT], (string) Factory::addressFromString($decShort, false, false, false));
        $hexShort = $ip->toHexadecimal();
        $this->assertSame($expected[self::HEX_SHORT], $hexShort);
        $this->assertSame($hexShort, $ip->toHexadecimal(false));
        $this->assertEquals($expected[self::DEC_SHORT], (string) Factory::addressFromString($hexShort, false, false, true));
        $hexLong = $ip->toHexadecimal(true);
        $this->assertSame($expected[self::HEX_LONG], $hexLong);
        $this->assertEquals($expected[self::DEC_SHORT], (string) Factory::addressFromString($hexLong, false, false, true));
    }
}
