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

    public function convert(string $amount, string $currency): float
    {
        $model = $this->currencyFactory->getCurrencyModel($currency);
        $amount = $amount / $model->getRate();

        return (float) $amount;
    }

    public function convertBack(float $amount, string $currency)
    {
        if ($amount === 0) {
            return 0;
        }

        $model = $this->currencyFactory->getCurrencyModel($currency);
        $amount = $model->getRate() * $amount;

        return $this->roundUp($amount, $model->getRoundPrecision());
    }

    public function roundUp($value, $precision = 0)
    {
        if ($value) {
            if (bccomp(strval($value / round($value, $precision)), strval(round(1, 2)), 100) !== 0) {
                $value = ceil($value * pow(10, $precision)) / pow(10, $precision);
            }
        }

        return number_format((float)$value, $precision, '.', '');
    }
}