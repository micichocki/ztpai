<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('GET')) {
            return $this->getUsers($entityManager);
        } elseif ($request->isMethod('DELETE')) {
            return $this->deleteCurrentUser($entityManager);
        } else {
            return new Response(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('users', name: 'users', methods: ['GET'])]
    public function getUsers(EntityManagerInterface $entityManager): JsonResponse
    {
        $currentUserId = $this->getUser()->getId();

        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        $usersData = [];
        foreach ($users as $user) {
            if ($user->getId() != $currentUserId) {
                $usersData[] = [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'first_name' => $user->getUserCredentials()->getName(),
                    'last_name' => $user->getUserCredentials()->getSurname(),
                    'created_at'=>$user->getUserCredentials()->getCreatedAt(),
                ];
            }
        }
        $data = [];
        $data['currentUserId'] = $currentUserId;
        $data['usersData'] = $usersData;

        return new JsonResponse($data);
    }

    #[Route('user/{user_id}', name: 'delete_user', methods: ['DELETE'])]
    private function deleteCurrentUser(EntityManagerInterface $entityManager, int $user_id, UserRepository $userRepository)
    {
        $user = $userRepository->findOneById($user_id);
        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse("user deleted");
    }
}