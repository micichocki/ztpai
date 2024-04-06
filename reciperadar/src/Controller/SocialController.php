<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SocialController extends AbstractController
{
    #[Route('/profile/{id}', name: 'view_profile', methods: ['GET'])]
    public function viewProfile($id): Response
    {
        return new Response("Viewing profile with ID: $id");
    }

    #[Route('/profile/edit', name: 'edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(): Response
    {
        return new Response('Edit profile page');
    }

    #[Route('/profile/{id}/add-friend', name: 'add_friend', methods: ['POST'])]
    public function addFriend($id): Response
    {
        return new Response("Adding friend for user with ID: $id");
    }

}
