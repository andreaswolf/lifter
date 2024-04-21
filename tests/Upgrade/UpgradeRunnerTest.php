<?php

namespace a9f\Lifter\Tests\Upgrade;

use a9f\Lifter\Configuration\LifterConfigFactory;
use a9f\Lifter\Upgrade\GitService;
use a9f\Lifter\Upgrade\StepExecutor;
use a9f\Lifter\Upgrade\UpgradeRunner;
use a9f\Lifter\Upgrade\UpgradeStep;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UpgradeRunnerTest extends TestCase
{
    #[Test]
    public function runnerCallsExecutorWithStepIfExecutorCanProcessStep(): void
    {
        $executor = $this->getStepRecordingExecutor();
        $step = $this->getUpgradeStepMock();

        $runner = $this->createRunner([$executor]);
        $runner->run([$step]);

        self::assertCount(1, $executor->executedSteps);
        self::assertSame($step, $executor->executedSteps[0]);
    }

    #[Test]
    public function runnerOnlyCallsFirstExecutorIfMultipleExecutorsCanProcesStep(): void
    {
        $firstExecutor = $this->getStepRecordingExecutor();
        $secondExecutor = $this->getStepRecordingExecutor();
        $step = $this->getUpgradeStepMock();

        $runner = $this->createRunner([$firstExecutor, $secondExecutor]);
        $runner->run([$step]);

        self::assertCount(1, $firstExecutor->executedSteps);
        self::assertSame($step, $firstExecutor->executedSteps[0]);
        self::assertCount(0, $secondExecutor->executedSteps);
    }

    #[Test]
    public function exceptionIsThrownIfNoExecutorCanExecuteStep(): void
    {
        $this->expectException(\RuntimeException::class);

        $executor = $this->getIncapableExecutor();
        $step = $this->getUpgradeStepMock();

        $runner = $this->createRunner([$executor]);
        $runner->run([$step]);
    }

    private function getUpgradeStepMock(): UpgradeStep
    {
        return new class() implements UpgradeStep {
            public function getCommitMessage(): string
            {
                return '';
            }
        };
    }

    /**
     * Creates an anonymous step executor that accepts all steps and records all passed steps
     *
     * @return StepExecutor&object{executedSteps: list<UpgradeStep>}
     */
    private function getStepRecordingExecutor()
    {
        return new class() implements StepExecutor {
            /** @var list<UpgradeStep> */
            public array $executedSteps = [];

            public function canExecute(UpgradeStep $step): bool
            {
                return true;
            }

            public function run(int $index, UpgradeStep $step): void
            {
                $this->executedSteps[] = $step;
            }
        };
    }

    /**
     * Creates an anonymous step executor that does not accept any step
     */
    private function getIncapableExecutor(): StepExecutor
    {
        return new class() implements StepExecutor {
            public function canExecute(UpgradeStep $step): bool
            {
                return false;
            }

            public function run(int $index, UpgradeStep $step): void
            {
            }
        };
    }

    /**
     * @param list<StepExecutor> $executors
     */
    private function createRunner(array $executors): UpgradeRunner
    {
        $configuration = LifterConfigFactory::createConfigurationFromFile(null);
        return new UpgradeRunner($configuration, $this->getCommitServiceMock(), $executors);
    }

    /**
     * @return GitService&MockObject
     */
    private function getCommitServiceMock()
    {
        return $this->getMockBuilder(GitService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
