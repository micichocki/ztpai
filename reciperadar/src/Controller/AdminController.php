<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class AdminController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users', methods: ['GET'])]
    public function showUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $userData = [];
        foreach ($users as $user) {
            $userData[] = [
                'id' => $user->getId(),
                'roles' => $user->getRoles(),
                'password' => $user->getPassword(),
                'userCredentials' => $user->getUserCredentials()
            ];
        }

        return new JsonResponse($userData);
    }

    #[Route('/admin/ingredients/add', name: 'admin_add_ingredient', methods: ['GET', 'POST'])]
    public function addIngredient(): JsonResponse
    {
        return new JsonResponse(["message" => "success"]);
    }

    #[Route('/admin/recipes/delete/{id}', name: 'admin_delete_recipe', methods: ['DELETE'])]
    public function deleteRecipe($id): JsonResponse
    {
        return new JsonResponse(["message" => "success"]);
    }

}