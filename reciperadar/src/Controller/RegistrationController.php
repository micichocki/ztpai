<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserCredentials;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_api_registration', methods: ['POST'])]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent(), true);

        if (empty($decoded['email']) || empty($decoded['password']) || empty($decoded['confirmPassword'])) {
            return $this->json(['error' => 'Email, password, and confirm password are required.'], 400);
        }

        $email = $decoded['email'];
        $plaintextPassword = $decoded['password'];
        $confirmPassword = $decoded['confirmPassword'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Invalid email format.'], 400);
        }

        if ($plaintextPassword !== $confirmPassword) {
            return $this->json(['error' => 'Passwords do not match.'], 400);
        }

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);

        $userCredentials = new UserCredentials();
        $user->setUserCredentials($userCredentials);
        $user->setRoles(['ROLE_USER']);
        $em->persist($userCredentials);

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Registered successfully!']);
    }
}

