<?php

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Upgrade\StepExecutor;
use a9f\Lifter\Upgrade\UpgradeStep;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * @implements StepExecutor<ShellStep>
 */
final class ShellStepExecutor implements StepExecutor
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly LifterConfig $config,
        private readonly InputInterface $input,
        private readonly OutputInterface $output
    ) {
        $this->io = new SymfonyStyle($this->input, $this->output);
    }

    public function canExecute(UpgradeStep $step): bool
    {
        return $step instanceof ShellStep;
    }

    public function run(UpgradeStep $step): void
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

        if ($process->getExitCode() > 0) {
            $this->io->error('');
        }
    }
}
