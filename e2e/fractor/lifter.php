<?php

use a9f\Fractor\Configuration\FractorConfigurationBuilder;
use a9f\FractorComposerJson\ChangePackageVersionComposerJsonFractor;
use a9f\FractorComposerJson\ValueObject\PackageAndVersion;
use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Upgrade\Step\FractorStep;
use a9f\Lifter\Upgrade\Step\RectorStep;

return static function (LifterConfig $config) {
    $config->withWorkingDirectory(__DIR__)
        ->withFractorBinary('vendor/bin/fractor')
        ->withFractorConfigFile(__DIR__ . '/fractor.php')
        ->setCommitResults(false);

    $config->withSteps([
        new FractorStep(
            'Update phpunit/phpunit to 11.x',
            static function (FractorConfigurationBuilder $fractorConfig) {
                return $fractorConfig
                    ->withConfiguredRule(
                        ChangePackageVersionComposerJsonFractor::class,
                        [
                            new PackageAndVersion('phpunit/phpunit', '^11.2')
                        ]
                    );
            }
        )
    ]);
};
