<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

use EtaOrionis\ComposerJsonManipulator\ComposerJson;

final readonly class RemovePackage implements ComposerPackageChange
{
    public function __construct(
        public string $packageName
    )
    {
    }

    public function apply(ComposerJson $manifest): void
    {
        $manifest->removePackage($this->packageName);
    }
}
