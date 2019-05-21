<?php
declare(strict_types=1);

namespace App\Service\Calculator;

use App\Exception\CalculatorNotFoundException;

class CalculatorFactory
{
    /**
     * @var CalculatorInterface[]
     */
    private $calculators;

    /**
     * @param $type
     * @return CalculatorInterface
     * @throws CalculatorNotFoundException
     */
    public function getCalculator($type): CalculatorInterface
    {
        if (!isset($this->calculators[$type])) {
            throw new CalculatorNotFoundException(
                sprintf('Calculator for "%s" type is not registered', $type)
            );
        }

        return $this->calculators[$type];
    }

    /**
     * Add last scan timestamp manager.
     *
     * @param CalculatorInterface $calculator
     * @param string              $type
     */
    public function addCalculator(CalculatorInterface $calculator, string $type): void
    {
        $this->calculators[$type] = $calculator;
    }
}