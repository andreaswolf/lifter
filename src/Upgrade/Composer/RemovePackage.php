<?php
declare(strict_types=1);

namespace a9f\Lifter\Upgrade\Composer;

final readonly class RemovePackage implements ComposerPackageChange
{
    public function __construct(
        public string $packageName
    )
    {
    }
}
