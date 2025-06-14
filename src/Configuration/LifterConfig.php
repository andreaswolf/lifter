<?php

namespace a9f\Lifter\Configuration;

use a9f\Lifter\Upgrade\UpgradeStep;

final class LifterConfig
{
    private string $workingDirectory = '';

    private string $commitMessagePrefix = '(lifter)';

    private ?string $rectorBinary = null;

    private ?string $rectorConfigFile = null;

    private ?string $fractorBinary = null;

    private ?string $fractorConfigFile = null;

    private bool $commitResults = true;

    private ?string $commitAuthor = null;
    private ?string $commitEMail = null;

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

    public function withCommitMessagePrefix(string $commitMessagePrefix): self
    {
        $this->commitMessagePrefix = $commitMessagePrefix;

        return $this;
    }

    public function getCommitMessagePrefix(): string
    {
        return $this->commitMessagePrefix;
    }

    public function withCommitAuthor(string $name, string $email): void
    {
        $this->commitAuthor = $name;
        $this->commitEMail = $email;
    }

    public function getCommitAuthor(): ?string
    {
        if ($this->commitAuthor === null || $this->commitEMail === null) {
            return null;
        }

        return sprintf('%s <%s>', $this->commitAuthor, $this->commitEMail);
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

    public function withRectorBinary(?string $rectorBinary): self
    {
        $this->rectorBinary = $rectorBinary;

        return $this;
    }

    public function getRectorBinary(): ?string
    {
        return $this->rectorBinary;
    }

    public function withRectorConfigFile(?string $rectorConfigFile): self
    {
        $this->rectorConfigFile = $rectorConfigFile;

        return $this;
    }

    public function getRectorConfigFile(): ?string
    {
        return $this->rectorConfigFile;
    }

    public function withFractorBinary(?string $fractorBinary): self
    {
        $this->fractorBinary = $fractorBinary;

        return $this;
    }

    public function getFractorBinary(): ?string
    {
        return $this->fractorBinary;
    }

    public function withFractorConfigFile(?string $fractorConfigFile): self
    {
        $this->fractorConfigFile = $fractorConfigFile;

        return $this;
    }

    public function getFractorConfigFile(): ?string
    {
        return $this->fractorConfigFile;
    }

    public function getCommitResults(): bool
    {
        return $this->commitResults;
    }

    public function setCommitResults(bool $commitResults): self
    {
        $this->commitResults = $commitResults;

        return $this;
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
