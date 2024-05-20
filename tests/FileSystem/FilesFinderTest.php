<?php

namespace a9f\Lifter\Tests\FileSystem;

use a9f\Lifter\FileSystem\FilesFinder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FilesFinderTest extends TestCase
{
    /**
     * @return \Generator<string, array{list<non-empty-string>, list<non-empty-string>}, void, null>
     */
    public static function pathPatternsProvider(): \Generator
    {
        yield 'pattern without matching files' => [
            [__DIR__ . '/does-not-exist.txt'],
            [],
        ];

        yield 'pattern with file path' => [
            [__DIR__ . '/Fixtures/file.txt'],
            [__DIR__ . '/Fixtures/file.txt'],
        ];

        yield 'pattern with simple wildcard' => [
            [__DIR__ . '/Fixtures/*.txt'],
            [__DIR__ . '/Fixtures/file.txt'],
        ];

        yield 'pattern with globstars' => [
            [__DIR__ . '/Fixtures/**.txt'],
            [__DIR__ . '/Fixtures/file.txt', __DIR__ . '/Fixtures/foo/file.txt', __DIR__ . '/Fixtures/foo/bar/file.txt'],
        ];
    }

    #[Test]
    #[DataProvider('pathPatternsProvider')]
    public function resolvePathPatternsReturnsFileMatchingPattern(array $patterns, array $expectedFiles): void
    {
        $subject = new FilesFinder();

        $result = $subject->resolvePathPatterns($patterns);

        self::assertSame($expectedFiles, $result);
    }
}