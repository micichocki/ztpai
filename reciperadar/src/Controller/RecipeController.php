<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Unit;
use App\Repository\RecipeRepository;
use App\Repository\TypeOfCuisineRepository;
use App\Repository\UserRepository;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($request->isMethod('DELETE')) {
            return $this->deleteRecipe($request, $entityManager);
        } elseif ($request->isMethod('GET')) {
            return $this->getRecipes($request, $entityManager);
        }elseif ($request->isMethod('POST')){
            return $this->createRecipe($request, $entityManager);
        }
        else {
            return new JsonResponse(null, 403);
        }
    }

    #[Route('/api/recipes/{id}', name: 'get_recipe_by_id', methods: ['GET'])]
    public function getRecipeById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            return new JsonResponse(['error' => 'Recipe not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $serializedRecipe = $this->serializer->serialize($recipe, 'json', ['groups' => 'recipe:read']);

        return new JsonResponse($serializedRecipe, ResponseAlias::HTTP_OK, [], true);
    }

    public function getRecipes(): JsonResponse
    {
        $recipes =$this->entityManager->getRepository(Recipe::class)->findAll();

        $serializedRecipes = [];
        foreach ($recipes as $recipe) {
            $ingredients = [];
            foreach ($recipe->getIngredients() as $ingredient) {
                $unit = [
                    'id' => $ingredient->getUnit()->getId(),
                    'name' => $ingredient->getUnit()->getName(),
                ];
                $ingredients[] = [
                    'name' => $ingredient->getName(),
                    'quantity' => $ingredient->getQuantity(),
                    'unit' => $unit,
                ];
            }

            $serializedRecipes[] = [
                'id' => $recipe->getId(),
                'name' => $recipe->getName(),
                'description' => $recipe->getDescription(),
                'ingredients' => $ingredients,
                'typeOfCuisine'=> $recipe->getTypeOfCuisine(),
            ];
        }

        return $this->json($serializedRecipes);
    }

    #[Route('recipes', name: 'createRecipe', methods: ['POST'])]
    public function createRecipe(Request $request, EntityManagerInterface $entityManager, TypeOfCuisineRepository $typeOfCuisineRepository, UserRepository $userRepository): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $name = $requestData['name'];
        $description = $requestData['description'];
        $typeOfCuisineName = $requestData['typeOfCuisine'];
        $ingredients = $requestData['ingredients'];
        $creatorId = $requestData['creator_id'];
        $creator = $userRepository->findOneById($creatorId);


        $recipe = new Recipe();
        $recipe->setCreator($creator);
        $recipe->setName($name);
        $recipe->setDescription($description);

        $typeOfCuisine = $typeOfCuisineRepository->findByName($typeOfCuisineName);

        $recipe->setTypeOfCuisine($typeOfCuisine);

        foreach ($ingredients as $ingredientData) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredientData['ingredient']);
            $ingredient->setQuantity($ingredientData['quantity']);

            $unit = $entityManager->getRepository(Unit::class)->find($ingredientData['unit']);
            $ingredient->setUnit($unit);

            $recipe->addIngredient($ingredient);
            $entityManager->persist($ingredient);
        }

        $entityManager->persist($recipe);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'recipe' => $recipe]);
    }


    #[Route('/api/recipes/{recipeId}/comments', name: 'addComent', methods: ['POST'])]
    public function addComment(Request $request, int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            return new JsonResponse(['error' => 'Recipe not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $content = $request->request->get('content');
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setRecipe($recipe);

        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'comment' => $comment]);
    }


    #[Route('/api/recipes/{id}', name: 'delete_recipe', methods: ['DELETE'])]
    public function deleteRecipe(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            return new JsonResponse(['error' => 'Recipe not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($recipe);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
    #[Route('api/users/{user_id}/recipes/{recipe_id}', name: 'recipe_add_to_favorites', methods: ['POST'])]
    public function addToFavorites(int $user_id,int $recipe_id, RecipeRepository $recipeRepository): JsonResponse
    {
        $user = $this->getUser();
        $recipe = $recipeRepository->findRecipeById($recipe_id);
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user->getUserCredentials()->addFollowedRecipe($recipe);
        $user->getUserCredentials()->incrementFollowersCount();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Recipe added to favorites successfully']);
    }

    #[Route('api/users/{user_id}/recipes/{recipe_id}', name: 'remove_from_favorites', methods: ['DELETE'])]
    public function removeFromFavorites(int $user_id,int $recipe_id, RecipeRepository $recipeRepository): JsonResponse
    {
        $user = $this->getUser();
        $recipe = $recipeRepository->findRecipeById($recipe_id);
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user->getUserCredentials()->removeFollowedRecipe($recipe);
        $user->getUserCredentials()->decrementFollowersCount();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Recipe removed from favorites successfully']);
    }


}