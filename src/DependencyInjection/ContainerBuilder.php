<?php

namespace a9f\Lifter\DependencyInjection;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\DependencyInjection\CompilerPass\CommandsCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ContainerBuilder
{
    public function build(?string $configurationFile): ContainerInterface
    {
        $containerBuilder = new SymfonyContainerBuilder();
        $containerBuilder->addCompilerPass(new CommandsCompilerPass());

        $lifterConfig = new LifterConfig();
        $containerBuilder->set(LifterConfig::class, $lifterConfig);

        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../config/'));
        $loader->load('services.yaml');

        if ($configurationFile !== null && is_file($configurationFile)) {
            $lifterConfig->import($configurationFile);
        }

        $containerBuilder->compile();

        return $containerBuilder;
    }
}