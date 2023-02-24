<?php

namespace App\Controller\Admin;

use App\Entity\LightLog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LightLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LightLog::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
