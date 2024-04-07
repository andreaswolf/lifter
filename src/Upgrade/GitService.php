<?php

namespace a9f\Lifter\Upgrade;

use a9f\Lifter\Configuration\LifterConfig;
use Symfony\Component\Process\Process;

/**
 * @final
 */
class GitService
{
    public function __construct(private readonly LifterConfig $config)
    {
    }

    public function performGitCommit(string $commitMessage): void
    {
        $process = new Process(
            [
                '/usr/bin/env',
                'git',
                'commit',
                '-m',
                $commitMessage
            ],
            $this->config->getWorkingDirectory(),
        );
        $process->run();

        if ($process->getExitCode() > 0) {
            throw new \RuntimeException(sprintf(
                "Could not perform Git commit in directory \"%s\": git binary returned code %d\n\n%s",
                $this->config->getWorkingDirectory(),
                $process->getExitCode(),
                $process->getErrorOutput()
            ));
        }
    }
}