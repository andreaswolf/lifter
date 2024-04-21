<?php

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Upgrade\Step\RectorStep;
use a9f\Lifter\Upgrade\Step\ShellStep;

return static function (LifterConfig $config) {
    $config->withWorkingDirectory(__DIR__)
        ->withRectorBinary('vendor/bin/rector')
        ->withRectorConfigFile(__DIR__ . '/rector.php')
        ->setCommitResults(false);

    $config->withSteps([
        new RectorStep(
            'Apply PHP 8.3 set list',
            static function (\Rector\Config\RectorConfig $rectorConfig) {
                $rectorConfig->sets([
                    \Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_83,
                ]);
            }
        )
    ]);
};
