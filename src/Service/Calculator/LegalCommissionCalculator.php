<?php
declare(strict_types=1);

namespace App\Service\Calculator;

use App\Model\Operation;

class LegalCommissionCalculator implements CalculatorInterface
{
    /** @var float */
    protected $commissionPercentage;

    /** @var float */
    protected $commissionMinAmount;

    /**
     * LegalCommissionCalculator constructor.
     * @param float $commissionPercentage
     * @param float $commissionMinAmount
     */
    public function __construct(float $commissionPercentage, float $commissionMinAmount)
    {
        $this->commissionPercentage = $commissionPercentage;
        $this->commissionMinAmount = $commissionMinAmount;
    }

    /**
     * @param Operation $operation
     * @return float
     */
    public function calculate(Operation $operation): float
    {
        $calculatedCommission = ($this->commissionPercentage/100) * $operation->getOperationAmountMain();

        return $calculatedCommission < $this->commissionMinAmount ? $this->commissionMinAmount : $calculatedCommission;
    }
}