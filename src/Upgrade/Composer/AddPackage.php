<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

use EtaOrionis\ComposerJsonManipulator\ComposerJson;

final readonly class AddPackage implements ComposerPackageChange
{
    public function __construct(
        public string $packageName,
        public string $version
    )
    {
    }

    public function apply(ComposerJson $manifest): void
    {
        $manifest->addRequiredPackage($this->packageName, $this->version);
    }
}
