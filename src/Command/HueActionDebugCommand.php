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
use App\Entity\Lights;

class HueActionDebugCommand extends Command {

    protected static $defaultName = 'hue:action-debug';
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
        $LightsEntity = $this->entity->getRepository(Lights::class);

        $Lights = $LightsEntity->findBy(['id' => 43]);

        foreach ($Lights as $Light) {
            $Action = $Light->getActions();
            foreach ($Action as $Ac) {
                dump($Ac);
            }
            #dump((count($Action) > 0));
            if (count($Action) > 0) {
                
            }
        }

        return 0;
    }

}
