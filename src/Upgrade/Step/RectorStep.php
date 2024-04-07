<?php

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Upgrade\UpgradeStep;
use Rector\Config\RectorConfig;

final class RectorStep implements UpgradeStep
{
    public function __construct(
        private readonly string $commitMessage,
        /**
         * @var \Closure{RectorConfig}: void $rectorClosure
         */
        public readonly \Closure $rectorClosure
    ) {
    }

    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }
}
