<?php

use a9f\Lifter\Configuration\ConfigResolver;
use a9f\Lifter\DependencyInjection\ContainerBuilder;
use a9f\Lifter\LifterApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$autoloadFile = (static function (): ?string {
    $candidates = [
        getcwd() . '/vendor/autoload.php',
        __DIR__ . '/../../../autoload.php',
        __DIR__ . '/../vendor/autoload.php',
    ];
    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            return $candidate;
        }
    }
    return null;
})();
if ($autoloadFile === null) {
    echo "Could not find autoload.php file";
    exit(1);
}
include $autoloadFile;

$configFile = ConfigResolver::resolveConfigsFromInput(new ArgvInput());
if (!file_exists($configFile)) {
    echo "Configuration file $configFile does not exist";
    exit(1);
}

$container = (new ContainerBuilder())->build($configFile);

/** @var LifterApplication $application */
$application = $container->get(LifterApplication::class);
$application->run($container->get(InputInterface::class), $container->get(OutputInterface::class));
