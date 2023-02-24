<?php

namespace App\Controller\Admin;

use App\Entity\DeviceSetting;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class DeviceSettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeviceSetting::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('deviceId'),
            ArrayField::new('config')            
        ];
    }
    
}
