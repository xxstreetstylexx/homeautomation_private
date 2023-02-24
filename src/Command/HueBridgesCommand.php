<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\HueService;
use App\Entity\Lights;
use App\Entity\LightBridges;
use App\Entity\LightLog;


class HueBridgesCommand extends Command
{
    protected static $defaultName = 'hue:bridges';
    protected static $defaultDescription = 'Setup/Modify/Delete Bridge';
    
    private $Hue;
    private $entity;
    
    public function __construct(HueService $Hue, EntityManagerInterface $entityManager)
    {
        
        $this->Hue = $Hue;
        
        $this->entity = $entityManager;
        
        parent::__construct();
        
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('bridge-ip', null, InputOption::VALUE_OPTIONAL, 'IP of the Bridge')
            ->addOption('bridge-id', null, InputOption::VALUE_OPTIONAL, 'ID of the existing Bridge (only EDIT, ADD and DELETE)')
            ->addOption('bridge-name', null, InputOption::VALUE_OPTIONAL, 'Set Bridge Name (only EDIT and ADD)')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Set password to Bridge (only EDIT and ADD)')
            ->addOption('mode', null, InputOption::VALUE_OPTIONAL, 'ADD/EDIT/DELETE')     
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $Bridges = $this->entity->getRepository(LightBridges::class);
                
        switch (true) {
            case strtoupper($input->getOption('mode')) == 'EDIT':
                $io->comment('EDIT');
                if ($input->getOption('password')  !== null) {
                    if ($input->getOption('bridge-ip') !== null) {
                    
                       $Bridge = $briges->findOneBy(['ip' => $input->getOption('bridge-ip')]);
                        
                        if ($Bridge !== null) {
                            $Bridge->setAccount($input->getOption('password'));             
                            $this->entity->persist($Bridge);
                            if ($this->entity->flush()) {
                                $io->success('ok');
                            }
                        } else {
                            
                            $io->error('--bridge-ip '.$input->getOption('bridge-ip').' not found');
                            
                        }
                                       
                    } elseif ($input->getOption('bridge-id') !== null) {
                        $Bridge = $briges->findOneBy(['id' => $input->getOption('bridge-id')]);
                        
                        if ($Bridge !== null) {
                            $Bridge->setAccount($input->getOption('password'));             
                            $this->entity->persist($Bridge);
                            if ($this->entity->flush()) {
                                $io->success('ok');
                            }
                        } else {
                            
                            $io->error('--bridge-id '.$input->getOption('bridge-id').' not found');
                            
                        }
                    } else {                        
                        $io->error('Required --bridge-ip OR --bridge-id');
                    }       
                } else {
                    $io->error('Required --password AND --bridge-ip OR --bridge-id');                    
                }
                break;
            case  strtoupper($input->getOption('mode')) == 'ADD':
                
                $io->comment('ADD');
                if ($input->getOption('bridge-ip') !== null) {
                    if ($input->getOption('password') !== null AND $input->getOption('bridge-name') !== null) {
                        $Bridge = new LightBridges();
                        $Bridge->setIp($input->getOption('bridge-ip'));
                        $Bridge->setAccount($input->getOption('password'));
                        $Bridge->setName($input->getOption('bridge-name'));
                        $this->entity->persist($Bridge);
                        if ($this->entity->flush()) {
                            $io->success('ok');
                        }
                    } else {
                        $io->error('Required --password AND --bridge-name'); 
                    }                    
                } else {
                    $io->error('Required --password AND --bridge-ip OR --bridge-id');   
                }
                break;
            case  strtoupper($input->getOption('mode')) == 'DELETE':
                
                $io->comment('DELETE');
                if ($input->getOption('bridge-id') !== null) {
                    $Bridge = $briges->findOneBy(['id' => $input->getOption('bridge-id')]);
                    if ($Bridge !== null) {
                        $this->entity->remove($Bridge);
                        if ($this->entity->flush()) {
                            $io->success('ok');
                        }
                    } else {
                        $io->error('--bridge-id '.$input->getOption('bridge-id').' not found');
                    }     
                } else {
                    $io->error('Required --bridge-id');   
                }                
                break;
            case  strtoupper($input->getOption('mode')) == 'LIST':
                
                $io->comment('LIST');
                $AllBridges = $Bridges->findAll();
                $rows = [];
                foreach ($AllBridges as $BridgeData) {
                    $rows[] = [$BridgeData->getId(), $BridgeData->getName(), $BridgeData->getIp(), $BridgeData->getAccount()];                    
                }
                
                $io->table(['id', 'name','ip', 'password'], $rows);
                break;
            default:
                
            
        }
        

        return 0;
    }
}
