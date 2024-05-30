<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\RecipeRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    private RecipeRepository $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    #[Route("/api/recipes/{id}/comments", name: "create_comment", methods: ["POST"])]
    public function create(int $id, Request $request): Response
    {
        var_dump($id);
        $recipe = $this->recipeRepository->findRecipeById($id);
        var_dump($recipe);
        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        $content = $request->getContent();
        $data = json_decode($content, true);

        if (!isset($data['content'])) {
            return $this->json(['error' => 'Comment content is missing'], Response::HTTP_BAD_REQUEST);
        }

        $comment = new Comment();
        $comment->setContent($data['content']);
        $comment->setRecipe($recipe);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->json(['message' => 'Comment created successfully'], Response::HTTP_CREATED);
    }


    #[Route("api/recipe/{recipeId}/comment/{commentId}", name: "update_comment", methods: ['PUT'])]
    public function updateComment(ManagerRegistry $doctrine, Request $request, int $recipeId, int $commentId): JsonResponse
    {
        $recipe = $this->recipeRepository->findRecipeById($recipeId);

        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        $comment = $doctrine->getRepository(Comment::class)->find($commentId);

        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        if ($comment->getRecipe()->getId() !== $recipe->getId()) {
            throw $this->createNotFoundException('Comment does not belong to the recipe');
        }

        $data = json_decode($request->getContent(), true);

        $comment->setContent($data['content']);

        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        return $this->json($comment, JsonResponse::HTTP_OK);
    }

    #[Route("api/recipe/{recipeId}/comment/{commentId}", name: "delete_comment", methods: ['DELETE'])]
    public function deleteComment(ManagerRegistry $doctrine ,int $recipeId, int $commentId): JsonResponse
    {
        $recipe = $this->recipeRepository->findRecipeById($recipeId);

        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        $comment = $doctrine->getRepository(Comment::class)->find($commentId);

        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        if ($comment->getRecipe()->getId() !== $recipe->getId()) {
            throw $this->createNotFoundException('Comment does not belong to the recipe');
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->json(['message' => 'Comment deleted'], Response::HTTP_OK);
    }
}
