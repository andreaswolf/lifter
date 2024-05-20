<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Upgrade\Composer\ComposerPackageChange;
use a9f\Lifter\Upgrade\StepExecutor;
use a9f\Lifter\Upgrade\UpgradeStep;

final class ComposerPackageStepExecutor implements StepExecutor
{
    public function __construct()
    {
    }

    public function canExecute(UpgradeStep $step): bool
    {
        return $step instanceof ComposerPackageChange;
    }

    public function run(int $index, UpgradeStep $step): void
    {
        // TODO: Implement run() method.
    }
}