<?php

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    /**
     * This is the default way input/output are initialized in symfony/console,
     * @see \Symfony\Component\Console\Application::run()
     */
    $services->set(InputInterface::class, ArgvInput::class)
        ->public();
    $services->set(OutputInterface::class, ConsoleOutput::class)
        ->public();
};