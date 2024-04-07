<?php

namespace a9f\Lifter\Upgrade;

final class UpgradeRunner
{
    /**
     * @param list<StepExecutor> $stepExecutors
     */
    public function __construct(
        private readonly GitService $gitService,
        private readonly array      $stepExecutors
    ) {
    }

    /**
     * @param list<UpgradeStep> $steps
     */
    public function run(array $steps): void
    {
        foreach ($steps as $step) {
            $executed = false;

            foreach ($this->stepExecutors as $executor) {
                if (!$executor->canExecute($step)) {
                    continue;
                }

                $executor->run($step);
                $executed = true;
                break;
            }

            if ($executed === false) {
                throw new \RuntimeException(sprintf(
                    'No executor registered for steps of class %s',
                    get_class($step)
                ));
            }

            $this->gitService->performGitCommit($step->getCommitMessage());
        }
    }
}
