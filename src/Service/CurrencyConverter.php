<?php
declare(strict_types=1);

namespace App\Service;

class CurrencyConverter
{
    /** @var CurrencyModelFactory */
    protected $currencyFactory;

    /**
     * CurrencyConverter constructor.
     * @param CurrencyModelFactory $currencyFactory
     */
    public function __construct(CurrencyModelFactory $currencyFactory)
    {
        $this->currencyFactory = $currencyFactory;
    }

    /**
     * @param string $amount
     * @param string $currency
     * @return float
     * @throws \App\Exception\CurrencyModelNotFoundException
     */
    public function convert(string $amount, string $currency): float
    {
        $model = $this->currencyFactory->getCurrencyModel($currency);
        $amount = $amount / $model->getRate();

        return (float) $amount;
    }

    /**
     * @param float  $amount
     * @param string $currency
     * @return string
     * @throws \App\Exception\CurrencyModelNotFoundException
     */
    public function convertBack(float $amount, string $currency): string
    {
        if ($amount === 0) {
            return '0';
        }

        $model = $this->currencyFactory->getCurrencyModel($currency);
        $amount = $model->getRate() * $amount;

        return $this->roundUp($amount, $model->getRoundPrecision());
    }

    /**
     * @param float $value
     * @param int   $precision
     * @return string
     */
    public function roundUp(float $value, int $precision = 0): string
    {
        if ($value) {
            if (bccomp(strval($value / round($value, $precision)), strval(round(1, 2)), 100) !== 0) {
                $value = ceil($value * pow(10, $precision)) / pow(10, $precision);
            }
        }

        return number_format((float)$value, $precision, '.', '');
    }
}