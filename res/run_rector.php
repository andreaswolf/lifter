<?php

use a9f\Lifter\Upgrade\Step\RectorStep;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) {
    $rectorConfigFile = getenv('RECTOR_CONFIG_FILE');
    if (!is_string($rectorConfigFile)) {
        throw new \RuntimeException('No file passed in env variable RECTOR_CONFIG_FILE', 1712507292);
    }
    $rectorConfig->import($rectorConfigFile);

    $lifterConfigFile = getenv('LIFTER_CONFIG_FILE');
    if (!is_string($lifterConfigFile)) {
        throw new \RuntimeException('No file passed in env variable LIFTER_CONFIG_FILE', 1712507293);
    }
    $lifterConfig = \a9f\Lifter\Configuration\LifterConfigFactory::createConfigurationFromFile($lifterConfigFile);

    $stepIndex = getenv('LIFTER_STEP_INDEX');
    if ((string)(int)$stepIndex !== $stepIndex) {
        throw new \RuntimeException(
            sprintf('Current step must be passed as LIFTER_STEP_INDEX, got "%s"', $stepIndex),
            1712507294
        );
    }

    $steps = $lifterConfig->getSteps();

    $step = $steps[(int)$stepIndex];

    if (!$step instanceof RectorStep) {
        throw new \RuntimeException(
            sprintf('Expected RectorStep, got %s', $step::class),
            1712507295
        );
    }

    ($step->rectorClosure)($rectorConfig);
};
