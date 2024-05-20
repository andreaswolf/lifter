<?php

namespace a9f\Lifter\FileSystem;

final readonly class FilesFinder
{
    /**
     * @param list<non-empty-string> $patterns
     * @return list<non-empty-string>
     */
    public function resolvePathPatterns(array $patterns): array
    {
        $files = [];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '**')) {
                $files[] = $this->resolveGlobstarPattern($pattern);
            } elseif (str_contains($pattern, '*')) {
                $files[] = glob($pattern);
            } elseif (file_exists($pattern)) {
                $files[] = [$pattern];
            }
        }

        return array_merge(...$files);
    }

    /**
     * @param string $pattern
     * @return list<non-empty-string>
     */
    private function resolveGlobstarPattern(string $pattern): array
    {
        [$prefix, $suffix] = explode('**', $pattern, 2);
        $prefix = rtrim($prefix, '/');

        $patterns = [];
        $globPattern = '';
        while ($dirs = glob($prefix . $globPattern, GLOB_ONLYDIR)) {
            foreach ($dirs as $item) {
                $patterns[] = sprintf('%s/*%s', $item, $suffix);
            }
            $globPattern .= '/*';
        }
        $patterns = array_unique($patterns);

        return $this->resolvePathPatterns($patterns);
    }
}