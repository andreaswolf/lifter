<?php

namespace a9f\Lifter\Configuration;

final class LifterConfigFactory
{
    public static function createConfigurationFromFile(?string $configurationFile): LifterConfig
    {
        $lifterConfig = new LifterConfig($configurationFile);

        if ($configurationFile !== null && is_file($configurationFile)) {
            $lifterConfig->import($configurationFile);
        }

        return $lifterConfig;
    }
}
