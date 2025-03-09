<?php

namespace App\Controller\Admin;

use App\Entity\BuildingResource;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class BuildingResourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BuildingResource::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
//            AssociationField::new('building'),
            AssociationField::new('resource'),
            NumberField::new('quantity'),
        ];
    }
}
