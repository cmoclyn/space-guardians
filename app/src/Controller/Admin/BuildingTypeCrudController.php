<?php

namespace App\Controller\Admin;

use App\Entity\BuildingType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class BuildingTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BuildingType::class;
    }

//    public function configureFields(string $pageName): iterable
//    {
//        return [
//            TextField::new('name'),
//            EmailField::new('email'),
//            TextField::new('password')->setFormType(PasswordType::class)->onlyOnForms(),
//            CollectionField::new('planets'),
//            CollectionField::new('playerExperiences'),
//        ];
//    }
}
