<?php

namespace App\Tests\DependencyInjection\CompilerPass;

use App\DependencyInjection\CompilerPass\CalculatorFactoryPass;
use App\Service\Calculator\CalculatorFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class CalculatorFactoryPassTest extends TestCase
{
    public function testProcess()
    {
        $containerMock = $this->createMock(ContainerBuilder::class);

        $taggedMock = [
            'id' => [
                [
                    'name' => 'commission.calculator',
                    'type' => 'cash_in',
                ],
            ]
        ];

        $definition = $this->createMock(Definition::class);
        $definition->expects(self::once())
            ->method('addMethodCall');

        $containerMock->expects(self::once())
            ->method('has')
            ->with(CalculatorFactory::class)
            ->willReturn(true);
        $containerMock->expects(self::once())
            ->method('findDefinition')
            ->with(CalculatorFactory::class)
            ->willReturn($definition);
        $containerMock->expects(self::once())
            ->method('findTaggedServiceIds')
            ->with('commission.calculator')
            ->willReturn($taggedMock);

        $pass = new CalculatorFactoryPass();

        $pass->process($containerMock);
    }

    public function testProcessHasNot()
    {
        $containerMock = $this->createMock(ContainerBuilder::class);

        $containerMock->expects(self::once())->method('has')->with(CalculatorFactory::class)->willReturn(false);

        $pass = new CalculatorFactoryPass();

        $pass->process($containerMock);
    }
}
