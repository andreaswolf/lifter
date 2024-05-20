<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

final readonly class AddPackage implements ComposerPackageChange
{
    public function __construct(
        public string $packageName,
        public string $version
    )
    {
    }
}