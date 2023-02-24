<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\PowerService;

class PowerCommand extends Command
{
    protected static $defaultName = 'power:check';
    protected static $defaultDescription = 'Get Power';
    protected $PowerService;
        
    public function __construct(PowerService $PowerService)
    {
        
        $this->PowerService = $PowerService;
        
        parent::__construct();
        
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $lastHour = $this->PowerService->getLastHour();
        
        foreach ($lastHour as $k) {
            dump($k);
        }
        
        return 0;
    }
}
