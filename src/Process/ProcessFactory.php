<?php

namespace a9f\Lifter\Process;

use a9f\Lifter\Configuration\LifterConfig;
use Symfony\Component\Process\Process;

final readonly class ProcessFactory
{
    public function __construct(private LifterConfig $config)
    {
    }

    public function createProcess(string ...$commandLine): Process
    {
        return new Process($commandLine, $this->config->getWorkingDirectory());
    }
}
