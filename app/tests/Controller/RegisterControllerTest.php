<?php

namespace App\Tests\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterControllerCanCreateUser(): void
    {
        $client = static::createClient();

        $client->jsonRequest(Request::METHOD_POST, '/register', $this->getRegisterData());

        $this->assertResponse($client->getResponse(), Response::HTTP_CREATED, 'Joueur enregistré');
    }

    public function testRegistrationWithExistingEmail(): void
    {
        $client = static::createClient();

        // Create a mock user in the database
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = $client->getContainer()->get(UserPasswordHasherInterface::class);

        $registerData = $this->getRegisterData();

        $existingPlayer = new Player();
        $existingPlayer->setEmail($registerData['email']);
        $existingPlayer->setName($registerData['username']);
        $existingPlayer->setPassword($passwordHasher->hashPassword($existingPlayer, 'SomePassword'));

        $entityManager->persist($existingPlayer);
        $entityManager->flush();

        $client->jsonRequest(Request::METHOD_POST, '/register', $registerData);

        $this->assertResponse($client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Email déjà existant');
    }

    private function assertResponse(Response $response, int $expectedStatusCode, string $expectedContent): void
    {
        $this->assertSame($expectedStatusCode, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertSame($expectedContent, $content['message']);
    }

    private function getRegisterData(): array
    {
        return [
            'email' => 'test@test.com',
            'username' => 'userTest',
            'password' => 'passwordTest'
        ];
    }
}
