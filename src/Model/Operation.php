<?php
declare(strict_types=1);

namespace App\Model;

class Operation
{
    const TYPE_NATURAL = 'natural';
    const TYPE_LEGAL = 'legal';

    const OPERATION_CASH_IN = 'cash_in';
    const OPERATION_CASH_OUT = 'cash_out';

    /** @var \DateTime */
    protected $date;

    /** @var int */
    protected $userId;

    /** @var string */
    protected $userType;

    /** @var string */
    protected $operationType;

    /** @var float */
    protected $operationAmount;

    /** @var float */
    protected $operationAmountMain;

    /** @var string */
    protected $currency;

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return Operation
     * @throws \Exception
     */
    public function setDate(string $date): Operation
    {
        $this->date = new \DateTime($date);

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Operation
     */
    public function setUserId(int $userId): Operation
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * @param string $userType
     * @return Operation
     */
    public function setUserType(string $userType): Operation
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * @param string $operationType
     * @return Operation
     */
    public function setOperationType(string $operationType): Operation
    {
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * @return float
     */
    public function getOperationAmount(): float
    {
        return $this->operationAmount;
    }

    /**
     * @param float $operationAmount
     * @return Operation
     */
    public function setOperationAmount(float $operationAmount): Operation
    {
        $this->operationAmount = $operationAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getOperationAmountMain(): float
    {
        return $this->operationAmountMain;
    }

    /**
     * @param float $operationAmountMain
     * @return Operation
     */
    public function setOperationAmountMain(float $operationAmountMain): Operation
    {
        $this->operationAmountMain = $operationAmountMain;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Operation
     */
    public function setCurrency(string $currency): Operation
    {
        $this->currency = $currency;

        return $this;
    }
}