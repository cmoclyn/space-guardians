<?php

namespace App\Controller\Admin;

use App\Entity\Resource;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Filesystem\Filesystem;

class ResourceCrudController extends AbstractCrudController
{
    public function __construct(private readonly Filesystem $filesystem) {}

    public static function getEntityFqcn(): string
    {
        return Resource::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        $renderDir = 'uploads/images/resource/';
        $uploadDir = sprintf('/public/%s/', $renderDir);
        $absoluteDir = sprintf('%s/%s', $projectDir, $uploadDir);

        if (!$this->filesystem->exists($absoluteDir)) {
            $this->filesystem->mkdir($absoluteDir);
        }

        return [
            TextField::new('name'),
            NumberField::new('coef')->setNumDecimals(2),
            ImageField::new('image')->setUploadDir($uploadDir)->setBasePath($renderDir),
        ];
    }
}
