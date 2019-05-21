<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\CurrencyModelNotFoundException;
use App\Model\Currency;
use App\Model\CurrencyInterface;

class CurrencyModelFactory
{
    /** @var CurrencyInterface[] */
    protected $currencyModels;

    /**
     * @param $currency
     * @return CurrencyInterface
     * @throws CurrencyModelNotFoundException
     */
    public function getCurrencyModel(string $currency)
    {
        $currency = strtoupper($currency);
        if (!isset($this->currencyModels[$currency])) {
            throw new CurrencyModelNotFoundException(
                sprintf('Model for "%s" currency is not registered', $currency)
            );
        }

        return $this->currencyModels[$currency];
    }

    /**
     * @param CurrencyInterface $currencyModel
     * @param string            $currency
     */
    public function addCurrencyModel(CurrencyInterface $currencyModel, $currency)
    {
        $this->currencyModels[$currency] = $currencyModel;
    }

    public function addCurrencies(array $currencySettings)
    {
        $main = new Currency();
        $main->setCurrency(strtoupper($currencySettings['main']['code']))
            ->setRate($currencySettings['main']['rate'])
            ->setRoundPrecision($currencySettings['main']['precision']);

        $this->addCurrencyModel($main, $main->getCurrency());

        foreach ($currencySettings['sub'] as $code => $options) {
            $code = strtoupper($code);
            $currency = new Currency();
            $currency->setCurrency($code)
                ->setRate($options['rate'])
                ->setRoundPrecision($options['precision']);
            $this->addCurrencyModel($currency, $code);
        }
    }
}