<?php

namespace a9f\Lifter\Configuration;

use a9f\Lifter\Upgrade\UpgradeStep;

final class LifterConfig
{
    private string $workingDirectory = '';

    public function __construct(public readonly ?string $configurationFile)
    {
        $this->workingDirectory = \Safe\getcwd();
    }

    public function withWorkingDirectory(string $workingDirectory): self
    {
        $this->workingDirectory = $workingDirectory;

        return $this;
    }

    public function getWorkingDirectory(): string
    {
        return $this->workingDirectory;
    }

    /**
     * @var list<UpgradeStep>
     */
    private array $steps = [];

    /**
     * @param list<UpgradeStep> $steps
     */
    public function withSteps(array $steps): self
    {
        $this->steps = array_merge($this->steps, $steps);

        return $this;
    }

    /**
     * @return list<UpgradeStep>
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    public function import(string $configFile): void
    {
        if (!file_exists($configFile)) {
            throw new \RuntimeException(sprintf('Config file %s does not exist, cannot import', $configFile));
        }

        $closure = (require $configFile);
        if (!is_callable($closure)) {
            throw new \RuntimeException(sprintf(
                'Fractor config files should return a callable for configuration, %s returned %s instead',
                $configFile,
                get_debug_type($closure)
            ));
        }

        /** @var callable(LifterConfig): void $closure */
        $closure($this);
    }
}
