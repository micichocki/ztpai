<?php

// src/Controller/UserCredentialsController.php

namespace App\Controller;

use App\Entity\UserCredentials;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserCredentialsController extends AbstractController
{
    private $entityManager;
    private $serializer;
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }
    #[Route('api/user_credentials/{id}', name: 'update_user_credentials', methods: ['PUT'])]
    public function updateUserCredentials(Request $request, UserCredentials $userCredentials): Response
    {
        $data = json_decode($request->getContent(), true);
        $userCredentials->setName($data['name'] ?? $userCredentials->getName());
        $userCredentials->setSurname($data['surname'] ?? $userCredentials->getSurname());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json($userCredentials, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }
}
