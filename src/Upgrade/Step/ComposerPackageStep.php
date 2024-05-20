<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Upgrade\Composer\ComposerPackageChange;
use a9f\Lifter\Upgrade\UpgradeStep;

final readonly class ComposerPackageStep implements UpgradeStep
{
    /**
     * @param list<ComposerPackageChange> $packageChanges
     */
    public function __construct(
        public string $commitMessage,
        private array $packageChanges
    )
    {
    }

    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }

    /**
     * @return list<ComposerPackageChange>
     */
    public function getPackageChanges(): array
    {
        return $this->packageChanges;
    }
}