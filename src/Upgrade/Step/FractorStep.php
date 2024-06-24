<?php

namespace a9f\Lifter\Upgrade\Step;

use a9f\Fractor\Configuration\FractorConfigurationBuilder;
use a9f\Lifter\Upgrade\UpgradeStep;

final class FractorStep implements UpgradeStep
{
    public function __construct(
        private readonly string  $commitMessage,
        /**
         * @var \Closure{FractorConfigurationBuilder}: void $fractorClosure
         */
        public readonly \Closure $fractorClosure
    ) {
    }

    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }
}
