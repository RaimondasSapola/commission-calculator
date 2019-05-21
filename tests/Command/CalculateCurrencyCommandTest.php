<?php

namespace App\Command;

use App\Exception\EmptyContentException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CalculateCurrencyCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $sampleResult = "0.60\n3.00\n0.00\n0.06\n0.90\n0\n0.70\n0.30\n0.30\n5.00\n0.00\n0.00\n8612\n";
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $file = $this->createTempFile();
        $command = $application->find('app:calculate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'path' => $file,
        ]);

        $output = $commandTester->getDisplay();

        $this->assertEquals($sampleResult, $output);

        unlink($file);
    }

    public function testExecuteFail()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $this->expectException(EmptyContentException::class);

        $file = 'path';
        $command = $application->find('app:calculate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'path' => $file,
        ]);
    }

    private function createTempFile()
    {
        $testData = '2014-12-31,4,natural,cash_out,1200.00,EUR
        2015-01-01,4,natural,cash_out,1000.00,EUR
        2016-01-05,4,natural,cash_out,1000.00,EUR
        2016-01-05,1,natural,cash_in,200.00,EUR
        2016-01-06,2,legal,cash_out,300.00,EUR
        2016-01-06,1,natural,cash_out,30000,JPY
        2016-01-07,1,natural,cash_out,1000.00,EUR
        2016-01-07,1,natural,cash_out,100.00,USD
        2016-01-10,1,natural,cash_out,100.00,EUR
        2016-01-10,2,legal,cash_in,1000000.00,EUR
        2016-01-10,3,natural,cash_out,1000.00,EUR
        2016-02-15,1,natural,cash_out,300.00,EUR
        2016-02-19,2,natural,cash_out,3000000,JPY';

        $tempFile = tempnam(sys_get_temp_dir(), 'test');

        $handle = fopen($tempFile, "w");
        fwrite($handle, $testData);
        fclose($handle);

        return $tempFile;
    }
}
