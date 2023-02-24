<?php

namespace App\Controller\Admin;

use App\Entity\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ActionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Actions::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [             
            AssociationField::new('Sensor'),            
            AssociationField::new('Lights'),
            TextField::new('Mode'),
            TextField::new('Operation'),
            IntegerField::new('Value'),
            BooleanField::new('Active'),
            TimeField::new('StartTime'),
            TimeField::new('EndTime'),            
        ];
    }
    
}
