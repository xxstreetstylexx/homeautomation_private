<?php

namespace App\Controller\Admin;

use App\Entity\PowerLog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class PowerLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PowerLog::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('SensorId')->setDisabled(),
            DateTimeField::new('LastUpdate')->setDisabled(),
            IntegerField::new('voltage')->setDisabled(),
            IntegerField::new('power')->setDisabled(),
            IntegerField::new('current')->setDisabled(),
        ];
    }
}
