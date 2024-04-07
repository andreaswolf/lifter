<?php

namespace a9f\Lifter\Upgrade;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @template T of UpgradeStep
 */
interface StepExecutor
{
    public function canExecute(UpgradeStep $step): bool;

    /**
     * @param T $step
     */
    public function run(UpgradeStep $step): void;
}