<?php

namespace App\Service\Calculator;

use App\Model\Operation;
use PHPUnit\Framework\TestCase;

class CashInCommissionCalculatorTest extends TestCase
{
    protected function setUp()
    {
        $percentage = 0.1;
        $maxAmount = 1;

        return new CashInCommissionCalculator($percentage, $maxAmount);
    }

    public function testCalculate()
    {
        $calculator = $this->setUp();

        $operationMock = $this->createMock(Operation::class);
        $operationMock->expects(self::once())->method('getOperationAmountMain')->willReturn(1000);

        $result = $calculator->calculate($operationMock);

        $this->assertEquals(1, $result);
    }

    public function testCalculate99999()
    {
        $calculator = $this->setUp();

        $operationMock = $this->createMock(Operation::class);
        $operationMock->expects(self::once())->method('getOperationAmountMain')->willReturn(99999);

        $result = $calculator->calculate($operationMock);

        $this->assertEquals(1, $result);
    }

    public function testCalculateBelowMax()
    {
        $calculator = $this->setUp();

        $operationMock = $this->createMock(Operation::class);
        $operationMock->expects(self::once())->method('getOperationAmountMain')->willReturn(500);

        $result = $calculator->calculate($operationMock);

        $this->assertEquals(0.5, $result);
    }
}
