<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\WeatherService;

class WeatherCommand extends Command
{
    protected static $defaultName = 'weather:check';
    protected static $defaultDescription = 'Test Weather Service';
    
    private $WeatherService;
    
    public function __construct(WeatherService $WeatherService)
    {
        
        $this->WeatherService = $WeatherService;
        
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
        
        $data = $this->WeatherService->updateWeather();
        
        $this->writeData($data);

        return 0;
    }
    
    private function writeData($data) {
        
        $dir = '/var/www/hue/_tmp/weather/unprogressed/';
        $file = \time().'.json';
        
        #file_put_contents($dir.$file, $data);
        
        file_put_contents($dir.'_current.json', $data);
        
    }
}
