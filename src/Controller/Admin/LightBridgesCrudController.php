<?php

namespace App\Controller\Admin;

use App\Entity\LightBridges;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LightBridgesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LightBridges::class;
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
