<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\HueService;

class HueAllOffCommand extends Command
{
    protected static $defaultName = 'hue:switch-all-off';
    protected static $defaultDescription = 'Add a short description for your command';
    
    private $Hue;
    
    public function __construct(HueService $Hue)
    {
        
        $this->Hue = $Hue;
        
        parent::__construct();
        
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('bridge-ip', 'i', InputOption::VALUE_REQUIRED, 'IP of the Bridge')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $bridgeIp = $input->getOption('bridge-ip');
        
        $data = $this->Hue->switchOffAllLight($bridgeIp);
        
        dump($data);

        return 0;
    }
}
