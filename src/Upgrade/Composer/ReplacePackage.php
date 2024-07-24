<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

use EtaOrionis\ComposerJsonManipulator\ComposerJson;

final readonly class ReplacePackage implements ComposerPackageChange
{
    public function __construct(
        public string $oldPackageName,
        public string $newPackageName,
        public string $version
    )
    {
    }

    public function apply(ComposerJson $manifest): void
    {
        $manifest->replacePackage($this->oldPackageName, $this->newPackageName, $this->version);
    }
}
