<?php
declare(strict_types=1);

namespace App\Service\Calculator;

use App\Model\Operation;

class NaturalCommissionCalculator implements CalculatorInterface
{
    /** @var float */
    protected $commissionPercentage;

    /** @var float */
    protected $maxFreeWithdrawAmount;

    /** @var int */
    protected $maxWithdrawOperations;

    /** @var array */
    protected $history = [];

    /** @var int */
    protected $weekInSeconds = 604800;

    /**
     * NaturalCommissionCalculator constructor.
     * @param float $commissionPercentage
     * @param float $maxFreeWithdrawAmount
     * @param int   $maxWithdrawOperations
     */
    public function __construct(float $commissionPercentage, float $maxFreeWithdrawAmount, int $maxWithdrawOperations)
    {
        $this->commissionPercentage = $commissionPercentage;
        $this->maxFreeWithdrawAmount = $maxFreeWithdrawAmount;
        $this->maxWithdrawOperations = $maxWithdrawOperations;
    }

    /**
     * @param Operation $operation
     * @return float
     */
    public function calculate(Operation $operation): float
    {
        $week = $this->getWeekIndex($operation->getDate());
        $usrId = $operation->getUserId();
        $isRepeated = array_key_exists($usrId, $this->history) &&
            array_key_exists($week, $this->history[$usrId]) &&
            $this->isRepeatedDate($this->history[$usrId][$week]['last_date'], $operation->getDate());
        if ($isRepeated) {
            $this->history[$usrId][$week]['count'] = $this->history[$usrId][$week]['count'] + 1;
            $this->history[$usrId][$week]['amount'] = $this->history[$usrId][$week]['amount'] + $operation->getOperationAmountMain();
            $this->history[$usrId][$week]['last_date'] = $operation->getDate();
        } else {
            $this->history[$usrId][$week]['count'] = 1;
            $this->history[$usrId][$week]['amount'] = $operation->getOperationAmountMain();
            $this->history[$usrId][$week]['last_date'] = $operation->getDate();
        }

        $shouldTaxOperation = $this->history[$usrId][$week]['count'] > $this->maxWithdrawOperations;

        if ($shouldTaxOperation) {
            return $this->getTax($operation->getOperationAmountMain());
        }

        $shouldTaxAmount = $this->history[$usrId][$week]['amount'] > $this->maxFreeWithdrawAmount;

        if ($shouldTaxAmount) {
            $taxAmount = $this->history[$usrId][$week]['amount'] - $operation->getOperationAmountMain() > $this->maxFreeWithdrawAmount ?
                $operation->getOperationAmountMain() :
                $this->history[$usrId][$week]['amount'] - $this->maxFreeWithdrawAmount;

            return $this->getTax((float)$taxAmount);
        }

        return 0;
    }

    /**
     * @param \DateTime $date
     * @return false|string
     */
    private function getWeekIndex(\DateTime $date): string
    {
        return date('W', $date->getTimestamp());
    }

    /**
     * @param float $amount
     * @return float
     */
    private function getTax(float $amount): float
    {
        return ($this->commissionPercentage/100) * $amount;
    }

    /**
     * @param \DateTime $previous
     * @param \DateTime $current
     * @return bool
     */
    private function isRepeatedDate(\DateTime $previous, \DateTime $current): bool
    {
        return $current->getTimestamp() - $previous->getTimestamp() < $this->weekInSeconds;
    }
}