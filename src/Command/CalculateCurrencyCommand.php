<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\OperationManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateCurrencyCommand extends Command
{
    protected static $defaultName = 'app:calculate';

    /** @var OperationManager */
    protected $operationManager;

    /**
     * CalculateCurrencyCommand constructor.
     * @param OperationManager $operationManager
     */
    public function __construct(OperationManager $operationManager)
    {
        parent::__construct();
        $this->operationManager = $operationManager;
    }


    protected function configure()
    {
        $this->addArgument('path', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commissionData = $this->operationManager->calculate($input->getArgument('path'));

        foreach ($commissionData as $commission) {
            $output->writeln($commission);
        }

    }
}