<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

use EtaOrionis\ComposerJsonManipulator\ComposerJson;

final readonly class ChangePackageVersion implements ComposerPackageChange
{
    public function __construct(
        public string $packageName,
        public string $version
    )
    {
    }

    public function apply(ComposerJson $manifest): void
    {
        foreach ($manifest->getRequirePackageNames() as $package) {
            $manifest->changePackageVersion($package, $this->version);
        }
    }
}
