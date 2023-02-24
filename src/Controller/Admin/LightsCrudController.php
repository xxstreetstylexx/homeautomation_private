<?php

namespace App\Controller\Admin;

use App\Entity\Lights;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LightsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lights::class;
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
