<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\HueService;

class HueGetGroupsCommand extends Command
{
    protected static $defaultName = 'hue:get-groups';
    protected static $defaultDescription = 'Return all Groups connected to the Bridge';
    
    private $Hue;
    private $entity;
    
    public function __construct(HueService $Hue)
    {
        $this->Hue = $Hue;
        
        parent::__construct();
        
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('force-update', 'f', InputOption::VALUE_OPTIONAL, 'Force an Update to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // Bridges laden
        $this->Hue->updateGroups();

        return 0;
    }
}
