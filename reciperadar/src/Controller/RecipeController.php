<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class RecipeController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/recipes/{recipeId}/comments', name: 'addComent', methods: ['POST'])]
    public function addComment(Request $request, int $id): JsonResponse
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            return new JsonResponse(['error' => 'Recipe not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $content = $request->request->get('content');
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setRecipe($recipe);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'comment' => $comment]);
    }

}