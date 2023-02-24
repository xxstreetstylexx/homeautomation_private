<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SensorService;
use App\Entity\Actions;

class HueActionCommand extends Command {

    protected static $defaultName = 'hue:action';
    protected static $defaultDescription = 'Check if action required';
    private $Sensor;
    private $entity;

    public function __construct(SensorService $Sensor, EntityManagerInterface $entityManager) {
        $this->Sensor = $Sensor;
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
        $ActionsEntity = $this->entity->getRepository(Actions::class);

        $Actions = $ActionsEntity->getActiveWithTime();
        
        $date = new \DateTime();
        
        foreach ($Actions as $Action) {
            foreach ($Action->getLights() as $LightObjects) {
                $this->Sensor->checkAction(
                        $Action->getSensor(),
                        $Action->getMode(),
                        $Action->getOperation(),
                        $Action->getValue(),
                        $LightObjects
                );
            }
        }

        return 0;
    }

}
