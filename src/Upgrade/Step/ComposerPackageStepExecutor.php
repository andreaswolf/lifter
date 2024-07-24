<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\FileSystem\FilesFinder;
use a9f\Lifter\Upgrade\Composer\ComposerPackageChange;
use a9f\Lifter\Upgrade\StepExecutor;
use a9f\Lifter\Upgrade\UpgradeStep;
use EtaOrionis\ComposerJsonManipulator\ComposerJson;

final class ComposerPackageStepExecutor implements StepExecutor
{
    public function __construct(private readonly LifterConfig $config, private readonly FilesFinder $filesFinder)
    {
    }

    public function canExecute(UpgradeStep $step): bool
    {
        return $step instanceof ComposerPackageStep;
    }

    /**
     * @param ComposerPackageStep $step
     */
    public function run(int $index, UpgradeStep $step): void
    {
        $files = $this->filesFinder->resolvePathPatterns($this->config->getComposerFiles());
        foreach ($files as $file) {
            $composerManifest = ComposerJson::fromFile($file);

            foreach ($step->getPackageChanges() as $change) {
                $change->apply($composerManifest);
            }
            $composerManifest->save($file);
        }
    }
}
