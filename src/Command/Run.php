<?php

namespace a9f\Lifter\Command;

use a9f\Lifter\Configuration\LifterConfig;
use a9f\Lifter\Upgrade\UpgradeRunner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'run'
)]
class Run extends Command
{
    public function __construct(
        private readonly UpgradeRunner $runner,
        private readonly LifterConfig $config,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Run an upgrade file')
            ->addOption('file', 'f',  InputOption::VALUE_REQUIRED, 'PHP file that contains all upgrade rules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->runner->run($this->config->getSteps());

        return Command::SUCCESS;
    }
}