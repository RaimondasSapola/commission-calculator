<?php
declare(strict_types=1);

namespace App\Service\Calculator;

use App\Model\Operation;

class CashInCommissionCalculator implements CalculatorInterface
{
    /** @var float */
    protected $commissionPercentage;

    /** @var float */
    protected $commissionMaxAmount;

    /**
     * CashInCommissionCalculator constructor.
     * @param float $commissionPercentage
     * @param float $commissionMaxAmount
     */
    public function __construct(float $commissionPercentage, float $commissionMaxAmount)
    {
        $this->commissionPercentage = $commissionPercentage;
        $this->commissionMaxAmount = $commissionMaxAmount;
    }

    /**
     * @param Operation $operation
     * @return float
     */
    public function calculate(Operation $operation): float
    {
        $calculatedCommission = ($this->commissionPercentage/100) * $operation->getOperationAmountMain();

        return $calculatedCommission > $this->commissionMaxAmount ? $this->commissionMaxAmount : $calculatedCommission;
    }
}