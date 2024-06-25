<?php

use a9f\Fractor\Configuration\FractorConfiguration;
use a9f\Lifter\Configuration\LifterConfigFactory;
use a9f\Lifter\Upgrade\Step\FractorStep;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Webmozart\Assert\Assert;

return static function (ContainerConfigurator $configurator) {
    $fractorConfig = FractorConfiguration::configure();

    // this is an additional file that can be used to configure Fractor before the individual step
    $fractorConfigFile = getenv('FRACTOR_CONFIG_FILE');
    if (!is_string($fractorConfigFile)) {
        throw new \RuntimeException('No file passed in env variable FRACTOR_CONFIG_FILE', 1712507292);
    }
    $fractorConfigClosure = (require $fractorConfigFile);
    Assert::isCallable($fractorConfigClosure, 'FRACTOR_CONFIG_FILE did not yield a callable');
    $fractorConfigClosure($fractorConfig);

    $lifterConfigFile = getenv('LIFTER_CONFIG_FILE');
    if (!is_string($lifterConfigFile)) {
        throw new \RuntimeException('No file passed in env variable LIFTER_CONFIG_FILE', 1712507293);
    }
    $lifterConfig = LifterConfigFactory::createConfigurationFromFile($lifterConfigFile);

    $stepIndex = getenv('LIFTER_STEP_INDEX');
    if ((string)(int)$stepIndex !== $stepIndex) {
        throw new \RuntimeException(
            sprintf('Current step must be passed as LIFTER_STEP_INDEX, got "%s"', $stepIndex),
            1712507294
        );
    }

    $steps = $lifterConfig->getSteps();

    $step = $steps[(int)$stepIndex];

    if (!$step instanceof FractorStep) {
        throw new \RuntimeException(
            sprintf('Expected FractorStep, got %s', $step::class),
            1719243901
        );
    }

    ($step->fractorClosure)($fractorConfig);

    $fractorConfig($configurator);
};
