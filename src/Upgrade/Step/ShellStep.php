<?php

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Upgrade\UpgradeStep;

final class ShellStep implements UpgradeStep
{
    public function __construct(
        private readonly string $commitMessage,
        public readonly string $script
    ) {
    }

    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }
}
