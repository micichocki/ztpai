<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserCredentials;
use Doctrine\Persistence\ManagerRegistry;
use OpenApi\Annotations as OA;
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
            return new JsonResponse(['error' => 'Email, password, and confirm password are required.'], JsonResponse::HTTP_BAD_REQUEST);        }

        $email = $decoded['email'];
        $plaintextPassword = $decoded['password'];
        $confirmPassword = $decoded['confirmPassword'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Invalid email format.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($plaintextPassword !== $confirmPassword) {
            return new JsonResponse(['error' => 'Passwords do not match.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return $this->json(['error' => 'Email already exists.'], 400);
        }

        if (strlen($plaintextPassword) < 4) {
            return new JsonResponse(['error' => 'Password must be at least 4 characters long.'], JsonResponse::HTTP_BAD_REQUEST);
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

