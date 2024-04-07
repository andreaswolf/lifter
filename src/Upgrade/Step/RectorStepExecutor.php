<?php

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Upgrade\StepExecutor;
use a9f\Lifter\Upgrade\UpgradeStep;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @implements StepExecutor<RectorStep>
 */
class RectorStepExecutor implements StepExecutor
{
    public function __construct(private readonly LifterConfig $config, private readonly OutputInterface $output)
    {
    }

    public function canExecute(UpgradeStep $step): bool
    {
        return $step instanceof RectorStep;
    }

    public function run(int $index, UpgradeStep $step): void
    {
        $rectorBinary = $this->config->getRectorBinary();
        if ($rectorBinary === null) {
            throw new \RuntimeException('Cannot run Rector: Binary path not set. Call \$lifterConfig->withRectorBinary() to fix this.');
        }

        $rectorConfigFile = $this->config->getRectorConfigFile();
        if ($rectorConfigFile === null) {
            throw new \RuntimeException('Cannot run Rector: Configuration file not set. Call \$lifterConfig->withRectorConfigFile() to fix this.');
        }

        $debugArguments = [];
        if ($this->output->isVerbose()) {
            $debugArguments[] = '-vv';
        }

        $process = new Process(
            [
                $rectorBinary,
                'process',
                '--no-progress-bar',
                '--no-diffs',
                ...$debugArguments,
                '--config',
                __DIR__ . '/../../../res/run_rector.php',
            ],
            env: [
                'RECTOR_CONFIG_FILE' => $rectorConfigFile,
                'LIFTER_CONFIG_FILE' => $this->config->configurationFile,
                'LIFTER_STEP_INDEX' => $index,
            ],
            timeout: 3600
        );
        $process->run(static function ($type, $data) {
            echo 'RECTOR: ' . $data;
        });
    }
}
