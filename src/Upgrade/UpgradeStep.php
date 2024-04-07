<?php

namespace a9f\Lifter\Upgrade;

interface UpgradeStep
{
    public function getCommitMessage(): string;
}
