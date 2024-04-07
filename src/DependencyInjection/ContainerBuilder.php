<?php

namespace a9f\Lifter\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ContainerBuilder
{
    public function build(): ContainerInterface
    {
        $containerBuilder = new SymfonyContainerBuilder();
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../config/'));
        $loader->load('services.yaml');

        return $containerBuilder;
    }
}