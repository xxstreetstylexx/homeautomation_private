<?php

namespace App\Controller\Admin;

use App\Entity\Rule;
use App\Entity\RuleSet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class RuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rule::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Name'),
            AssociationField::new('targetSensor'),
            BooleanField::new('Active'),
            BooleanField::new('allTrue')            
        ];
    }
}
