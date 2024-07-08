<?php

namespace a9f\Lifter\Upgrade\Step;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Upgrade\StepExecutor;
use a9f\Lifter\Upgrade\UpgradeStep;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @implements StepExecutor<FractorStep>
 */
class FractorStepExecutor implements StepExecutor
{
    public function __construct(private readonly LifterConfig $config, private readonly OutputInterface $output)
    {
    }

    public function canExecute(UpgradeStep $step): bool
    {
        return $step instanceof FractorStep;
    }

    public function run(int $index, UpgradeStep $step): void
    {
        $fractorBinary = $this->config->getFractorBinary();
        if ($fractorBinary === null) {
            throw new \RuntimeException('Cannot run Fractor: Binary path not set. Call \$lifterConfig->withFractorBinary() to fix this.');
        }

        $fractorConfigFile = $this->config->getFractorConfigFile();
        if ($fractorConfigFile === null) {
            throw new \RuntimeException('Cannot run Fractor: Configuration file not set. Call \$lifterConfig->withFractorConfigFile() to fix this.');
        }

        $debugArguments = [];
        if ($this->output->isVerbose()) {
            $debugArguments[] = '-vv';
        } else {
            $debugArguments[] = '--quiet';
        }

        $process = new Process(
            [
                $fractorBinary,
                'process',
                ...$debugArguments,
                '--config',
                __DIR__ . '/../../../res/run_fractor.php',
            ],
            env: [
                'FRACTOR_CONFIG_FILE' => $fractorConfigFile,
                'LIFTER_CONFIG_FILE' => $this->config->configurationFile,
                'LIFTER_STEP_INDEX' => $index,
            ],
            timeout: 3600
        );
        $process->run(static function ($type, $data) {
            $data = trim($data);

            // TODO do we need to handle CRLF separately here?
            $lines = explode("\n", $data);
            $lines = array_map(
                static fn (string $line) => "FRACTOR: $line",
                $lines
            );
            echo implode("\n", $lines), "\n";
        });

        if ($process->getExitCode() !== 0) {
            throw new \RuntimeException('Running Fractor failed: ' . $process->getErrorOutput(), 1719244358);
        }
    }
}
