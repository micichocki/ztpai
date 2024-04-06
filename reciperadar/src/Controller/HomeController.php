<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home', methods: ['GET'])]
    public function home(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }

    #[Route('/recipe/{id}', name: 'view_recipe', methods: ['GET'])]
    public function viewRecipe($id): Response
    {
        return new Response("Viewing recipe with ID: $id");
    }

    #[Route('/recipe/{id}/comment/add', name: 'add_comment', methods: ['POST'])]
    public function addComment($id): Response
    {
        return new Response("Adding comment to recipe with ID: $id");
    }

    #[Route('/recipe/add', name: 'add_recipe', methods: ['GET', 'POST'])]
    public function addRecipe(): Response
    {
        return new Response('Add recipe page');
    }

    #[Route('/recipe/{id}/favorite', name: 'add_favorite', methods: ['POST'])]
    public function addFavorite($id): Response
    {
        return new Response("Adding recipe with ID: $id to favorites");
    }
}
