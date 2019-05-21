<?php

namespace App\Service;

use App\Model\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{
    protected function getFactoryMock()
    {
        return $this->createMock(CurrencyModelFactory::class);
    }

    protected function getModelMock()
    {
        return $this->createMock(Currency::class);
    }

    public function testRoundUp()
    {
        $factoryMock = $this->getFactoryMock();
        $factoryMock->expects(self::never())->method('getCurrencyModel');

        $converter = new CurrencyConverter($factoryMock);

        $this->assertEquals('2.4', $converter->roundUp(2.33, 1));
        $this->assertEquals('2.4', $converter->roundUp(2.40000000000001, 1));
        $this->assertEquals('2.4', $converter->roundUp(2.3423423423, 1));
        $this->assertEquals('2.33', $converter->roundUp(2.33, 2));
        $this->assertEquals('2.4', $converter->roundUp(2.33, 1));
        $this->assertEquals('2.4', $converter->roundUp(2.33, 1));
    }

    public function testConvertOneToOne()
    {
        $currency = 'EUR';
        $factoryMock = $this->getFactoryMock();
        $currencyMock = $this->getModelMock();
        $currencyMock->expects(self::once())->method('getRate')->willReturn(1);
        $factoryMock->expects(self::once())->method('getCurrencyModel')->with($currency)->willReturn($currencyMock);

        $converter = new CurrencyConverter($factoryMock);

        $result = $converter->convert(100, $currency);

        $this->assertEquals(100, $result);
    }

    public function testConvertOneToTwo()
    {
        $currency = 'EUR';
        $factoryMock = $this->getFactoryMock();
        $currencyMock = $this->getModelMock();
        $currencyMock->expects(self::once())->method('getRate')->willReturn(0.5);
        $factoryMock->expects(self::once())->method('getCurrencyModel')->with($currency)->willReturn($currencyMock);

        $converter = new CurrencyConverter($factoryMock);

        $result = $converter->convert(100, $currency);

        $this->assertEquals(200, $result);
    }

    public function testConvertOneToTwoBack()
    {
        $currency = 'EUR';
        $factoryMock = $this->getFactoryMock();
        $currencyMock = $this->getModelMock();
        $currencyMock->expects(self::once())->method('getRate')->willReturn(0.5);
        $factoryMock->expects(self::once())->method('getCurrencyModel')->with($currency)->willReturn($currencyMock);

        $converter = new CurrencyConverter($factoryMock);

        $result = $converter->convertBack(200, $currency);

        $this->assertEquals(100, $result);
    }
}
