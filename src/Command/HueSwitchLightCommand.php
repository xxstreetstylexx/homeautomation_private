<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\HueService;

class HueSwitchLightCommand extends Command
{
    protected static $defaultName = 'hue:switch-light';
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
            ->addOption('light-id', 'l', InputOption::VALUE_REQUIRED, 'ID of the Light')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $bridgeIp = $input->getOption('bridge-ip');
        $lightId = $input->getOption('light-id');
        
        $data = $this->Hue->SwitchLight($bridgeIp, $lightId);
        
        dump($data);

        return 0;
    }
}
