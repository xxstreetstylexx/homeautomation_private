<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\RuleService;

class HueRuleCheckCommand extends Command {

    protected static $defaultName = 'hue:rule-check';
    protected static $defaultDescription = 'Check if action required';
    private $Rule;
    private $entity;

    public function __construct(RuleService $Rule, EntityManagerInterface $entityManager) {
        $this->Rule = $Rule;
        $this->entity = $entityManager;
        parent::__construct();
    }

    protected function configure(): void {
        $this
                ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);
        /*
         * $this->Sensor->checkAction(10, 'temperature', 'max', 2250, 22);
         */
        $this->Rule->checkRules();

        return 0;
    }

}
