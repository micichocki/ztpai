<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\RecipeRepository;

class HomeController extends AbstractController
{

    #[Route('/recipe/{id}', name: 'view_recipe', methods: ['GET'])]
    public function viewRecipe($id, RecipeRepository $recipeRepository): JsonResponse
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            return new JsonResponse(["message" => "failed"]);
        }

        $recipeData = [
            'id' => $recipe->getId(),
            'name' => $recipe->getName(),
            'ingredients' => $recipe->getIngredients(),
            'typeOfCuisine' => $recipe->getTypeOfCuisine(),
        ];

        return new JsonResponse($recipeData);
    }

    #[Route('/recipe/{id}/comment/add', name: 'add_comment', methods: ['POST'])]
    public function addComment($id): JsonResponse
    {
        return new JsonResponse(["message" => "success"]);
    }

    #[Route('/recipe/add', name: 'add_recipe', methods: ['GET', 'POST'])]
    public function addRecipe(Request $request): JsonResponse
    {
        if ($request->isMethod('POST')) {
            return new JsonResponse(["message" => "success"]);
        }

        return new JsonResponse(["message" => "success"]);
    }

    #[Route('/recipe/{id}/favorite', name: 'add_favorite', methods: ['POST'])]
    public function addFavorite($id): JsonResponse
    {
        return new JsonResponse(["message" => "success"]);
    }
}
