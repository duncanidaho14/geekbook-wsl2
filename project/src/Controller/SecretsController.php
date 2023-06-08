<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SecretsController extends AbstractController
{
    #[Route('/secrets', name: 'app_secrets')]
    public function index(): JsonResponse
    {
        // Keep your Stripe API key protected by including it as an environment variable
        // or in a private script that does not publicly expose the source code.

        // This is your test secret API key.
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SecretsController.php',
        ]);
    }
}
