<?php

namespace a9f\Lifter\DependencyInjection;

use a9f\Lifter\DependencyInjection\CompilerPass\CommandsCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ContainerBuilder
{
    public function build(): ContainerInterface
    {
        $containerBuilder = new SymfonyContainerBuilder();
        $containerBuilder->addCompilerPass(new CommandsCompilerPass());

        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../config/'));
        $loader->load('services.yaml');

        $containerBuilder->compile();

        return $containerBuilder;
    }
}