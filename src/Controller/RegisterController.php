<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;

class RegisterController extends AbstractController
{
    // Make sure the route for login here should be unique or probably should be removed if it's a copy-paste error
    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encrypt the password
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

            // Set roles or other necessary fields
            $user->setRoles(['ROLE_USER']);

            // Save the new user
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to the login page or another appropriate route
            return $this->redirectToRoute('app_login');
        } else {
            // Log form errors
            foreach ($form->getErrors(true) as $error) {
                $this->logger->error(sprintf("Form error: %s", $error->getMessage()));
            }
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
