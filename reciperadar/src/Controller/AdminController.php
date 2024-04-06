<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users', methods: ['GET'])]
    public function showUsers(): Response
    {
        return new Response('Admin panel - Users');
    }

    #[Route('/admin/ingredients/add', name: 'admin_add_ingredient', methods: ['GET', 'POST'])]
    public function addIngredient(): Response
    {
        return new Response('Admin panel - Add Ingredient');
    }

    #[Route('/admin/recipes/delete/{id}', name: 'admin_delete_recipe', methods: ['DELETE'])]
    public function deleteRecipe($id): Response
    {
        return new Response("Admin panel - Delete Recipe with ID: $id");
    }

}