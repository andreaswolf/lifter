<?php

namespace Configuration;

use a9f\Lifter\Configuration\LifterConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LifterConfigTest extends TestCase
{
    #[Test]
    public function workingDirectoryIsSetToCurrentWorkingDirectoryByDefault(): void
    {
        $initialWorkingDirectory = getcwd();
        self::assertSame(getcwd(), (new LifterConfig())->getWorkingDirectory());

        try {
            chdir(__DIR__);
            self::assertSame(__DIR__, (new LifterConfig())->getWorkingDirectory());
        } finally {
            chdir($initialWorkingDirectory);
        }
    }
}