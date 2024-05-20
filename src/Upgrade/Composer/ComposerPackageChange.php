<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

use EtaOrionis\ComposerJsonManipulator\ComposerJson;

interface ComposerPackageChange
{
    public function apply(ComposerJson $manifest): void;
}