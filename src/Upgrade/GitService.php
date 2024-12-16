<?php

namespace a9f\Lifter\Upgrade;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Process\ProcessFactory;

/**
 * @final
 */
class GitService
{
    public function __construct(
        private readonly LifterConfig $config,
        private readonly ProcessFactory $processFactory
    ) {
    }

    public function doGitCommitForStep(UpgradeStep $step): void
    {
        $commitMessage = $step->getCommitMessage();
        if ($this->config->getCommitMessagePrefix() !== '') {
            $commitMessage = sprintf('%s %s', $this->config->getCommitMessagePrefix(), $commitMessage);
        }

        $process = $this->processFactory->createProcess(
            '/usr/bin/env',
            'git',
            'commit',
            '-a',
            '--allow-empty',
            '-m',
            $commitMessage
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
