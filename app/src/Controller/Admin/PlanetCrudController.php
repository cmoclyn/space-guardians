<?php

namespace App\Controller\Admin;

use App\Entity\Planet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlanetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Planet::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('solarSystem'),
            NumberField::new('positionX'),
            NumberField::new('positionY'),
        ];
    }
}
