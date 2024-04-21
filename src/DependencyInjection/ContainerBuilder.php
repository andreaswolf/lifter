<?php

namespace a9f\Lifter\DependencyInjection;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Configuration\LifterConfigFactory;
use a9f\Lifter\DependencyInjection\CompilerPass\CommandsCompilerPass;
use a9f\Lifter\DependencyInjection\CompilerPass\StepExecutorCompilerPass;
use a9f\Lifter\Upgrade\StepExecutor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ContainerBuilder
{
    public function build(?string $configurationFile): ContainerInterface
    {
        $containerBuilder = new SymfonyContainerBuilder();
        $containerBuilder->addCompilerPass(new CommandsCompilerPass());

        $containerBuilder->registerForAutoconfiguration(StepExecutor::class)
            ->addTag(StepExecutorCompilerPass::EXECUTOR_SERVICE_TAG);
        $containerBuilder->addCompilerPass(new StepExecutorCompilerPass());

        $lifterConfig = LifterConfigFactory::createConfigurationFromFile($configurationFile);
        $containerBuilder->set(LifterConfig::class, $lifterConfig);

        $configFileLocator = new FileLocator(__DIR__ . '/../../config/');
        $yamlLoader = new YamlFileLoader($containerBuilder, $configFileLocator);
        $yamlLoader->load('services.yaml');
        $phpLoader = new PhpFileLoader($containerBuilder, $configFileLocator);
        $phpLoader->load('application.php');

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
