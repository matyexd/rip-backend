<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(#[CurrentUser] UserInterface $user): JsonResponse
    {
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(),
        ]);
    }
}
