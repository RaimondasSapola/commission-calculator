<?php

namespace App\Service\Calculator;

use App\Model\Operation;

interface CalculatorInterface
{
    public function calculate(Operation $operation): float;
}