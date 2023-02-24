<?php

namespace App\Controller\Admin;

use App\Entity\Sensors;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
## use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SensorsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sensors::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('bridge'),
            TextField::new('type'),
            ArrayField::new('state')->hideOnIndex(),
            IdField::new('internalId'),
            BooleanField::new('reachable'),
            BooleanField::new('virtual'),
            TextField::new('uniqueid'),
            DateTimeField::new('checktime')
        ];
    }
    
}
