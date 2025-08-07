<?php

namespace App\Controller\Admin;

use App\Entity\Building;
use App\Service\ChartService;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class BuildingCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
        private readonly ChartService $chartService,
    ) {}

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public static function getEntityFqcn(): string
    {
        return Building::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextareaField::new('description'),
            AssociationField::new('type'),
            CollectionField::new('basePrices')->useEntryCrudForm()->setEntryIsComplex(),
        ];
    }

    public function detail(AdminContext $context)
    {
        parent::detail($context);
        /** @var Building $building */
        $building = $context->getEntity()->getInstance();

        $costChart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $costChart->setData($this->chartService->getCostsData($building));
        $costChart->setOptions(['scales' => ['y' => ['beginAtZero' => true]]]);

        $timeChart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $timeChart->setData($this->chartService->getConstructionTimeData($building));

        $productionData = $this->chartService->getProductionData($building);
        $productionChart = null;

        if ($productionData) {
            $productionChart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
            $productionChart->setData($productionData);
        }

        $storageData = $this->chartService->getStorageData($building);
        $storageChart = null;

        if ($storageData) {
            $storageChart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
            $storageChart->setData($storageData);
        }

        return $this->render('admin/building/detail.html.twig', [
            'pageName' => Crud::PAGE_DETAIL,
            'entity' => $context->getEntity(),
            'costChart' => $costChart,
            'timeChart' => $timeChart,
            'productionChart' => $productionChart,
            'storageChart' => $storageChart,
        ]);
    }
}
