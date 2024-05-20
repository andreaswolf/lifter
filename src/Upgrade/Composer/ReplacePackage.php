<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

final readonly class ReplacePackage implements ComposerPackageChange
{
    public function __construct(
        public string $oldPackageName,
        public string $newPackageName,
        public string $version
    )
    {
    }
}
