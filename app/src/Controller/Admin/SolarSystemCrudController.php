<?php

namespace App\Controller\Admin;

use App\Entity\SolarSystem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SolarSystemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SolarSystem::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            NumberField::new('positionX'),
            NumberField::new('positionY'),
            AssociationField::new('galaxy'),
        ];
    }
}
