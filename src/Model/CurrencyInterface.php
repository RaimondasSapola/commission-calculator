<?php

namespace App\Model;

interface CurrencyInterface
{
    public function getRate(): float;

    public function setRate(float $rate);

    public function getRoundPrecision(): int;

    public function setRoundPrecision(int $amount);

    public function getCurrency(): string;

    public function setCurrency(string $amount);
}