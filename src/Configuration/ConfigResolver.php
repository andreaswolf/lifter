<?php

namespace a9f\Lifter\Configuration;

use Symfony\Component\Console\Input\ArgvInput;

final class ConfigResolver
{
    public static function resolveConfigsFromInput(ArgvInput $input): ?string
    {
        return self::getConfigFileFromInput($input);
    }

    private static function getConfigFileFromInput(ArgvInput $input): ?string
    {
        // TODO validate if the file exists
        return self::getOptionValue($input, ['--file', '-f']);
    }

    /**
     * @param list<string> $nameCandidates
     */
    private static function getOptionValue(ArgvInput $input, array $nameCandidates): ?string
    {
        foreach ($nameCandidates as $name) {
            if ($input->hasParameterOption($name, true)) {
                return $input->getParameterOption($name, null, true);
            }
        }

        return null;
    }
}
