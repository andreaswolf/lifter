<?php

namespace a9f\Lifter\DependencyInjection\CompilerPass;

use a9f\Fractor\Fractor\FractorRunner;
use a9f\Lifter\Upgrade\UpgradeRunner;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class StepExecutorCompilerPass implements CompilerPassInterface
{
    public const EXECUTOR_SERVICE_TAG = 'lifter.step_executor';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(UpgradeRunner::class)) {
            return;
        }

        $taggedServices = $container->findTaggedServiceIds(self::EXECUTOR_SERVICE_TAG);
        $references = array_map(static fn ($id) => new Reference($id), array_keys($taggedServices));

        $definition = $container->findDefinition(UpgradeRunner::class);
        $definition->setArgument('$stepExecutors', $references);
    }
}