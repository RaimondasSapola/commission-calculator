<?php
declare(strict_types=1);

namespace App\Model;

class Currency implements CurrencyInterface
{
    /** @var float */
    protected $rate;

    /** @var string */
    protected $currency;

    /** @var int */
    protected $roundPrecision;

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     * @return Currency
     */
    public function setRate(float $rate): Currency
    {
        $this->rate = $rate;

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
     * @param int $roundPrecision
     * @return Currency
     */
    public function setRoundPrecision(int $roundPrecision): Currency
    {
        $this->roundPrecision = $roundPrecision;

        return $this;
    }

    /**
     * @return int
     */
    public function getRoundPrecision(): int
    {
        return $this->roundPrecision;
    }

    /**
     * @param string $currency
     * @return Currency
     */
    public function setCurrency(string $currency): Currency
    {
        $this->currency = $currency;

        return $this;
    }
}