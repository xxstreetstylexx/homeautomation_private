<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\HueService;

use WebSocket\Client;
use WebSocket\ConnectionException;
class WebsocketCommand extends Command
{
    protected static $defaultName = 'hue:websocket';
    protected static $defaultDescription = 'Add a short description for your command';
    
    private $Hue;
    private $WS;
    
    public function __construct(HueService $Hue, Client $Websocket)
    {
        
        $this->Hue = $Hue;
        
        $this->WS = $Websocket;
                
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
        
        $this->WS->setup('ws://192.168.2.214:443/', ['timeout' => 15, 'persistent' => true]);
        
        while (true) {
            try {
                $messages = json_decode($this->WS->receive(), true);
                if($messages['r'] == 'sensors' && $messages['t'] == 'event') {
                    $io->block(print_r($messages, true));
                }
              } catch (ConnectionException $e) {
                // Possibly log errors
                #$io->error($e->getMessage());
            }
        }
        $this->WS->close();
        
        
        
        return 0;
    }
}
