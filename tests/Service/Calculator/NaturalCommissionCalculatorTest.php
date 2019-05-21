<?php

namespace App\Service\Calculator;

use App\Model\Operation;
use PHPUnit\Framework\TestCase;

class NaturalCommissionCalculatorTest extends TestCase
{
    public function testCalculate0()
    {
        $operationMock = $this->createMock(Operation::class);
        $operationMock->expects(self::once())->method('getUserId');
        $operationMock->expects(self::exactly(2))->method('getDate')->willReturn(new \DateTime());

        $calculator = new NaturalCommissionCalculator(1, 2, 1);
        $result = $calculator->calculate($operationMock);
        $this->assertEquals(0, $result);
    }

    public function testCalculate1()
    {
        $operationMock = $this->createMock(Operation::class);
        $operationMock->expects(self::once())->method('getUserId');
        $operationMock->expects(self::exactly(2))->method('getDate')->willReturn(new \DateTime());
        $operationMock->expects(self::exactly(2))->method('getOperationAmountMain')->willReturn(100);
        $calculator = new NaturalCommissionCalculator(1, 0, 1);
        $result = $calculator->calculate($operationMock);
        $this->assertEquals(1, $result);
    }

    public function testCalculate50PercentAboveLimit()
    {
        $operationMock = $this->createMock(Operation::class);
        $operationMock->expects(self::once())->method('getUserId');
        $operationMock->expects(self::exactly(2))->method('getDate')->willReturn(new \DateTime());
        $operationMock->expects(self::exactly(2))->method('getOperationAmountMain')->willReturn(150);
        $calculator = new NaturalCommissionCalculator(1, 100, 1);
        $result = $calculator->calculate($operationMock);
        $this->assertEquals(0.5, $result);
    }
}
