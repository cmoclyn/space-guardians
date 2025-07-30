<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

class FlashController extends AbstractController {

    #[Route('/flash/messages', name: 'flash_messages')]
    public function messages(RequestStack $requestStack): JsonResponse
    {
        $session = $requestStack->getSession();
        $flashes = $session->getFlashBag()->all();

        return $this->json($flashes);
    }
}