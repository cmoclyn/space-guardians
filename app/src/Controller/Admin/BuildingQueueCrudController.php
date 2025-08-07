<?php

namespace App\Controller\Admin;

use App\Entity\QueueBuilding;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BuildingQueueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QueueBuilding::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('planetBuilding'),
            DateTimeField::new('startedAt'),
            DateTimeField::new('finishedAt'),
        ];
    }
}
