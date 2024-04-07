<?php

namespace a9f\Lifter\Tests\DependencyInjection;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ContainerBuilderTest extends TestCase
{
    #[Test]
    public function lifterConfigObjectCanBeRetrievedFromBuiltContainer(): void
    {
        $subject = new ContainerBuilder();

        $result = $subject->build(null);

        self::assertInstanceOf(LifterConfig::class, $result->get(LifterConfig::class));
    }

    #[Test]
    public function buildImportsConfigurationFileIntoConfigObjectIfGiven(): void
    {
        $subject = new ContainerBuilder();

        $GLOBALS['called'] = false;

        $result = $subject->build(__DIR__ . '/Fixtures/lifter_config.php');

        self::assertTrue($GLOBALS['called']);
    }
}