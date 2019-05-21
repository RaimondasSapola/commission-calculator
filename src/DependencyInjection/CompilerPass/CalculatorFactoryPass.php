<?php
declare(strict_types=1);

namespace App\DependencyInjection\CompilerPass;

use App\Service\Calculator\CalculatorFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CalculatorFactoryPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CalculatorFactory::class)) {
            return;
        }

        $definition = $container->findDefinition(CalculatorFactory::class);
        $tagged = $container->findTaggedServiceIds('commission.calculator');

        foreach ($tagged as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addCalculator', [new Reference($id), $attributes['type']]);
            }
        }
    }
}