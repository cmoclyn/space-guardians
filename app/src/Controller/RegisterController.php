<?php

namespace App\Controller;

use App\DTO\RegisterDTO;
use App\Entity\Player;
use App\Event\Player\PlayerCreatedEvent;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] RegisterDTO $registerDTO): JsonResponse
    {
        if (null !== $this->playerRepository->findOneBy(['email' => $registerDTO->email])) {
            return new JsonResponse(['message' => 'Email déjà existant'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $player = new Player();
        $player->setEmail($registerDTO->email);
        $player->setName($registerDTO->username);

        $hashedPassword = $this->passwordHasher->hashPassword($player, $registerDTO->password);

        $player->setPassword($hashedPassword);

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new PlayerCreatedEvent($player));

        return new JsonResponse(['message' => 'Joueur enregistré'], Response::HTTP_CREATED);
    }
}