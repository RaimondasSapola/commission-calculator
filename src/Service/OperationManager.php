<?php
declare(strict_types=1);

namespace App\Service;

use App\Enum\ImportEnum;
use App\Exception\EmptyContentException;
use App\Model\Operation;
use App\Service\Calculator\CalculatorFactory;

class OperationManager
{
    /** @var CurrencyConverter */
    protected $converter;

    /** @var CalculatorFactory */
    protected $calculatorFactory;

    /**
     * OperationManager constructor.
     * @param CurrencyConverter           $converter
     * @param CalculatorFactory  $calculatorFactory
     */
    public function __construct(
        CurrencyConverter $converter,
        CalculatorFactory $calculatorFactory
    ) {
        $this->converter = $converter;
        $this->calculatorFactory = $calculatorFactory;
    }

    /**
     * @param string $path
     * @return array
     * @throws EmptyContentException
     */
    public function calculate(string $path)
    {
        if (!file_exists($path)) {
            throw new EmptyContentException();
        }

        return $this->getData($path);
    }

    private function getData(string $path){
        $commissionData = [];
        foreach ($this->operationEntry($path) as $value) {
            $operation = $this->createOperationModel($value);
            $commission = null;

            $type = $operation->getOperationType() === Operation::OPERATION_CASH_IN ?
                Operation::OPERATION_CASH_IN :
                $operation->getUserType();

            $calculator = $this->calculatorFactory->getCalculator($type);
            $commission = $calculator->calculate($operation);

            $commissionData[] = $this->converter->convertBack($commission, $operation->getCurrency());
        }

        return $commissionData;
    }

    private function operationEntry($filePath)
    {
        $file = fopen($filePath, 'r');

        while (($line = fgetcsv($file)) !== false) {
            yield $line;
        }

        fclose($file);
    }

    private function createOperationModel(array $operation)
    {
        $model = new Operation();

        $model
            ->setCurrency($operation[ImportEnum::OPERATION_CURRENCY])
            ->setDate($operation[ImportEnum::OPERATION_DATE])
            ->setOperationAmount((float) $operation[ImportEnum::OPERATION_SUM])
            ->setOperationAmountMain(
                $this->converter->convert(
                    $operation[ImportEnum::OPERATION_SUM],
                    $operation[ImportEnum::OPERATION_CURRENCY]
                )
            )
            ->setUserId((int) $operation[ImportEnum::CONSUMER_IDENTIFIER])
            ->setUserType($operation[ImportEnum::CONSUMER_TYPE])
            ->setOperationType($operation[ImportEnum::OPERATION_TYPE]);

        return $model;
    }
}