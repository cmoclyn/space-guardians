<?php

namespace App\EventListener;

use App\Service\BuildingService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

readonly class RequestListener
{
    public function __construct(
        private BuildingService $buildingService,
    ) {}

    #[AsEventListener]
    public function onRequest(RequestEvent $event): void
    {
        $this->buildingService->checkFinishedBuildings();
    }
}