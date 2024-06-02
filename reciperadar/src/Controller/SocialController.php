<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\UserRepository;

class SocialController extends AbstractController
{
    #[Route('/profile/{id}', name: 'view_profile', methods: ['GET'])]
    public function viewProfile($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
            'userCredentials' => $user->getUserCredentials()
        ];

        return new JsonResponse($userData);
    }

//    #[Route('/profile/edit', name: 'edit_profile', methods: ['GET', 'POST'])]
//    public function editProfile(): JsonResponse
//    {
//        return new JsonResponse(["message" => "success"]);
//    }
//
//    #[Route('/profile/{id}/add-follow', name: 'add_follow', methods: ['POST'])]
//    public function addFollow($id): JsonResponse
//    {
//        return new JsonResponse(["message" => "success"]);
//    }
//
//    #[Route('/add-credentials', name: 'add_follow', methods: ['POST', 'GET'])]
//    public function addCredentials(): JsonResponse
//    {
//        return new JsonResponse(["message" => "success"]);
//    }

}
