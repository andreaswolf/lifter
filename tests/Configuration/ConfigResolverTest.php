<?php

namespace a9f\Lifter\Tests\Configuration;

use a9f\Lifter\Configuration\ConfigResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;

class ConfigResolverTest extends TestCase
{
    #[Test]
    public function resolveConfigsFromInputReturnsNullForEmptyInput(): void
    {
        $result = ConfigResolver::resolveConfigsFromInput(new ArgvInput(['bin/lifter']));

        self::assertNull($result);
    }

    #[Test]
    public function resolveConfigsFromInputReturnsFileIfGivenWithLongFormOption(): void
    {
        $result = ConfigResolver::resolveConfigsFromInput(new ArgvInput(['bin/lifter', '--file=foo.php']));

        self::assertSame('foo.php', $result);
    }

    #[Test]
    public function resolveConfigsFromInputReturnsFileIfGivenWithShortFormOption(): void
    {
        $result = ConfigResolver::resolveConfigsFromInput(new ArgvInput(['bin/lifter', '-f', 'foo.php']));

        self::assertSame('foo.php', $result);
    }
}
