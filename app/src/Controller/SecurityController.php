<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\User;
use App\Form\RegistrationUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
    ): Response {
        $user = new User();

        $form = $this->createForm(RegistrationUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planet = $form->get('originalPlayer')->get('planets')->getData();


            $player = $user->getOriginalPlayer();
            $player->addPlanet($planet);
            $player->setOriginalOwner($user);
            $user->addPlayer($player);

            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.',
        );
    }
}
