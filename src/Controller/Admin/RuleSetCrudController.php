<?php

namespace App\Controller\Admin;

use App\Entity\RuleSet;
use App\Entity\Rule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class RuleSetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RuleSet::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('Rule'),
            AssociationField::new('Sensor'),
            TextField::new('Mode'),
            TextField::new('Operation'),
            IntegerField::new('Value'),
            BooleanField::new('Active'),
        ];
    }
    
}
