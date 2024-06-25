<?php

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Upgrade\StepExecutor;
use a9f\Lifter\Upgrade\UpgradeStep;
use Symfony\Component\Process\Process;

/**
 * @implements StepExecutor<ShellStep>
 */
final class ShellStepExecutor implements StepExecutor
{
    public function __construct(private readonly LifterConfig $config)
    {
    }

    public function canExecute(UpgradeStep $step): bool
    {
        return $step instanceof ShellStep;
    }

    public function run(int $index, UpgradeStep $step): void
    {
        $process = new Process(
            [
                '/usr/bin/env',
                'bash',
                '-s', // read commands from stdin
            ],
            cwd: $this->config->getWorkingDirectory(),
            input: $step->script,
            timeout: 600
        );
        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \RuntimeException("Running shell step failed:\n\n" . $process->getErrorOutput(), 1719244358);
        }
    }
}
