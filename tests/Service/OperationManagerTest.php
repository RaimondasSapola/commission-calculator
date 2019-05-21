<?php

namespace App\Service;

use App\Model\Operation;
use App\Service\Calculator\CalculatorFactory;
use App\Service\Calculator\CashInCommissionCalculator;
use App\Service\Calculator\LegalCommissionCalculator;
use App\Service\Calculator\NaturalCommissionCalculator;
use PHPUnit\Framework\TestCase;

class OperationManagerTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testCalculate($data, $type)
    {
        switch ($type) {
            case Operation::OPERATION_CASH_IN:
                $calculatorMock = $this->createMock(CashInCommissionCalculator::class);
                $calculatorMock->expects(self::once())->method('calculate')->willReturn(1);
                break;
            case Operation::TYPE_LEGAL:
                $calculatorMock = $this->createMock(NaturalCommissionCalculator::class);
                $calculatorMock->expects(self::once())->method('calculate')->willReturn(1);
                break;
            case Operation::TYPE_NATURAL:
                $calculatorMock = $this->createMock(LegalCommissionCalculator::class);
                $calculatorMock->expects(self::once())->method('calculate')->willReturn(1);
                break;
        }

        $converterMock = $this->createMock(CurrencyConverter::class);
        $converterMock->expects(self::once())->method('convertBack');
        $calculatorFactoryMock = $this->createMock(CalculatorFactory::class);

        $calculatorFactoryMock->expects(self::exactly(1))->method('getCalculator')->with($type)->willReturn($calculatorMock);
        $manager = new OperationManager($converterMock, $calculatorFactoryMock);

        $manager->calculate($this->createTempFile($data));
    }

    private function createTempFile($data)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test');

        $handle = fopen($tempFile, "w");

        fwrite($handle, $data);
        fclose($handle);

        return $tempFile;
    }

    public function additionProvider()
    {
        return [
            ['2014-12-31,4,natural,cash_out,1200.00,EUR', 'natural'],
            ['2015-01-01,4,natural,cash_out,1000.00,EUR', 'natural'],
            ['2016-01-05,4,natural,cash_out,1000.00,EUR', 'natural'],
            ['2016-01-05,1,natural,cash_in,200.00,EUR', 'cash_in'],
            ['2016-01-06,2,legal,cash_out,300.00,EUR', 'legal'],
            ['2016-01-06,1,natural,cash_out,30000,JPY', 'natural'],
            ['2016-01-07,1,natural,cash_out,1000.00,EUR', 'natural'],
            ['2016-01-07,1,natural,cash_out,100.00,USD', 'natural'],
            ['2016-01-10,1,natural,cash_out,100.00,EUR', 'natural'],
            ['2016-01-10,2,legal,cash_in,1000000.00,EUR', 'cash_in'],
            ['2016-01-10,3,natural,cash_out,1000.00,EUR', 'natural'],
            ['2016-02-15,1,natural,cash_out,300.00,EUR', 'natural'],
            ['2016-02-19,2,natural,cash_out,3000000,JPY', 'natural'],
        ];
    }
}
