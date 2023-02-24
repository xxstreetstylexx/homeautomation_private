<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\SensorService;

class HueGetSensorsCommand extends Command
{
    protected static $defaultName = 'hue:get-sensors';
    protected static $defaultDescription = 'Return all Sensors connected to the Bridge';
    
    private $Sensor;
    private $entity;
    
    public function __construct(SensorService $Sensor)
    {
        $this->Sensor = $Sensor;
        
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
        $this->Sensor->updateSensors();

        return 0;
    }
}
