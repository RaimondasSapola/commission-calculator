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

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): Currency
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setRoundPrecision(int $roundPrecision): Currency
    {
        $this->roundPrecision = $roundPrecision;

        return $this;
    }

    public function getRoundPrecision(): int
    {
        return $this->roundPrecision;
    }

    public function setCurrency(string $currency): Currency
    {
        $this->currency = $currency;

        return $this;
    }
}