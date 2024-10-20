<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        #[Route('/api/login', name: 'api_login', methods: ['POST'])]
        public function login(): JsonResponse
        {
            // This method is intentionally left empty. The login logic is handled by Symfony's security system.
            return new JsonResponse(['status' => 'You are logged in.'], JsonResponse::HTTP_OK);
        }
    }
}
