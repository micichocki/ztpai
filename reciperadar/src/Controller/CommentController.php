<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CommentController extends AbstractController
{
    private RecipeRepository $recipeRepository;
    public function __construct(RecipeRepository $recipeRepository, private ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->recipeRepository = $recipeRepository;
    }

    #[Route("/api/recipes/{id}/comments", name: "create_comment", methods: ["POST"])]
    public function create(int $id, #[CurrentUser] User $user, Request $request): Response
    {
        $recipe = $this->recipeRepository->findRecipeById($id);
        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }
        $post_data = json_decode($request->getContent(), true);
        $content = $post_data['content'];
        if (!isset($content) or $content=='') {
            return $this->json(['error' => 'Comment content is missing'], Response::HTTP_BAD_REQUEST);
        }
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setRecipe($recipe);
        $comment->setCreator($user);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
        return $this->json(['message' => 'Comment created successfully'], Response::HTTP_CREATED);
    }


    #[Route("api/recipes/{recipeId}/comments/{commentId}", name: "update_comment", methods: ['PUT'])]
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

    #[Route("api/recipes/{recipeId}/comments/{commentId}", name: "delete_comment", methods: ['DELETE'])]
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
