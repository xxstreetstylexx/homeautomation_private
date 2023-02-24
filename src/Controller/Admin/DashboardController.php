<?php

namespace App\Controller\Admin;

/*
 * Entity
 */
use App\Entity\Device;
use App\Entity\DeviceSetting;
use App\Entity\LightBridges;
use App\Entity\LightGroups;
use App\Entity\LightLog;
use App\Entity\PowerLog;
use App\Entity\Lights;
use App\Entity\Actions;
use App\Entity\Scenes;
use App\Entity\Sensors;
use App\Entity\Rule;
use App\Entity\RuleSet;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hue');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Lights / Plugs');
        yield MenuItem::linkToCrud('Lights / Plugs', 'fa fa-pencil', Lights::class);
        yield MenuItem::linkToCrud('Actions', 'fa fa-filter', Actions::class);
        yield MenuItem::linkToCrud('Light Logs', 'fa fa-file', LightLog::class);
        yield MenuItem::linkToCrud('Power Logs', 'fa fa-file', PowerLog::class);
        yield MenuItem::section('Groups');
        yield MenuItem::linkToCrud('Groups', 'fa fa-layer-group', LightGroups::class);
        yield MenuItem::section('Sensors');
        yield MenuItem::linkToCrud('Sensors', 'fa fa-star', Sensors::class);
        yield MenuItem::section('Bridges');        
        yield MenuItem::linkToCrud('Bridges', 'fa fa-sitemap', LightBridges::class);
        yield MenuItem::section('Devices');
        yield MenuItem::linkToCrud('Device', 'fa fa-microchip', Device::class);
        yield MenuItem::linkToCrud('Settings', 'fa fa-wrench', DeviceSetting::class);
        yield MenuItem::section('Scenes');
        yield MenuItem::linkToCrud('Scenes', 'fa fa-folder', Scenes::class);
        yield MenuItem::section('Rules');
        yield MenuItem::linkToCrud('Rule', 'fa fa-gear', Rule::class);
        yield MenuItem::linkToCrud('RuleSets', 'fa fa-gears', RuleSet::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
    
   
}
