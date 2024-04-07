<?php

namespace a9f\Lifter\Upgrade;

use a9f\Lifter\Configuration\LifterConfig;

final class UpgradeRunner
{
    /**
     * @param list<StepExecutor> $stepExecutors
     */
    public function __construct(
        private readonly LifterConfig $lifterConfig,
        private readonly GitService $gitService,
        private readonly array      $stepExecutors
    ) {
    }

    /**
     * @param list<UpgradeStep> $steps
     */
    public function run(array $steps): void
    {
        $currentStepIndex = 0;
        foreach ($steps as $step) {
            $executed = false;

            foreach ($this->stepExecutors as $executor) {
                if (!$executor->canExecute($step)) {
                    continue;
                }

                $executor->run($currentStepIndex, $step);
                $executed = true;
                break;
            }

            if ($executed === false) {
                throw new \RuntimeException(sprintf(
                    'No executor registered for steps of class %s',
                    get_class($step)
                ));
            }

            if ($this->lifterConfig->getCommitResults()) {
                $this->gitService->performGitCommit($step->getCommitMessage());
            }
            ++$currentStepIndex;
        }
    }
}
