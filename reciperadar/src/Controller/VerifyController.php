<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class VerifyController extends AbstractController
{
    private JWTEncoderInterface $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }
    #[Route('/jwt_verify', name: 'view_recipe', methods: ['POST'])]
    public function verify(Request $request, UserRepository $userRepository): JsonResponse
    {
        $authorizationHeader = $request->headers->get('Authorization');

        if (!$authorizationHeader) {
            return $this->json(['valid' => false, 'error' => 'Token not provided'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $jwtToken = str_replace('Bearer ', '', $authorizationHeader);

        try {
            $decodedToken = $this->jwtEncoder->decode($jwtToken);
            $email = $decodedToken['username'];
            $user = $userRepository->findOneByEmail($email);

            $user_credentials = $user->getUserCredentials();

            if (!$user_credentials) {
                return $this->json(['valid' => false, 'error' => 'User credentials not found'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            if($user_credentials->getName()){
                $name = $user_credentials->getName();
            }else{
                $name='';
            }
            if($user_credentials->getSurname()){
                $surname = $user_credentials->getSurname();
            }else{
                $surname = '';
            }

            $followedRecipes = $user_credentials->getFollowedRecipes()->toArray();
            $followedRecipeIds = array_map(fn($recipe) => $recipe->getId(), $followedRecipes);

            $serializedCredentials = [
                'id' => $user_credentials->getId(),
                'surname'=> $surname,
                'name'=> $name,
                'followed_recipes' => $followedRecipeIds,
                'followers_count'=>$user_credentials->getFollowersCount(),
            ];

            return $this->json([
                'valid' => true,
                'user_id' => $user->getId(),
                'user_role' => $user->getRoles(),
                'email' => $user->getEmail(),
                'user_credentials' => $serializedCredentials]);
        } catch (\Exception $e) {
            return $this->json([
                'valid' => false,
                'error' => 'Invalid token',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }
}