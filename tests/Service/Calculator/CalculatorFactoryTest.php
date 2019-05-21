<?php

namespace App\Service\Calculator;

use App\Exception\CalculatorNotFoundException;
use PHPUnit\Framework\TestCase;

class CalculatorFactoryTest extends TestCase
{
    public function testFactory()
    {
        $factory = new CalculatorFactory();

        $this->expectException(CalculatorNotFoundException::class);

        $factory->getCalculator('anything');

        $calculator = $this->crateMock(CashInCommissionCalculator::class);
        $factory->addCalculator($calculator, 'test');

        $result = $factory->getCalculator('test');

        $this->assertEquals($calculator, $result);
    }
}
