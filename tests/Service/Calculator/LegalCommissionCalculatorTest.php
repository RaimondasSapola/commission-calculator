<?php

namespace App\Service\Calculator;

use App\Model\Operation;
use PHPUnit\Framework\TestCase;

class LegalCommissionCalculatorTest extends TestCase
{
    protected function setUp()
    {
        $percentage = 0.1;
        $minAmount = 1;

        return new LegalCommissionCalculator($percentage, $minAmount);
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
        $operationMock->expects(self::once())->method('getOperationAmountMain')->willReturn(1);

        $result = $calculator->calculate($operationMock);

        $this->assertEquals(1, $result);
    }

    public function testCalculateAboveMin()
    {
        $calculator = $this->setUp();

        $operationMock = $this->createMock(Operation::class);
        $operationMock->expects(self::once())->method('getOperationAmountMain')->willReturn(2000);

        $result = $calculator->calculate($operationMock);

        $this->assertEquals(2, $result);
    }
}
