<?php

namespace App\Controller;

use App\RequestHandler\RequestHandlerInterface;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserController extends AbstractController
{
    #[Route('/user/all', name: 'app_users', methods: 'GET')]
    public function all(SerializerInterface $objectSerializer, UserService $userService): JsonResponse
    {
        return $this->json([
            'users' => $objectSerializer->serialize($userService->findAll(), 'json', ['groups' => 'presentation'])
        ], Response::HTTP_OK);
    }

    #[Route('/user/{id}', name: 'app_user', methods: 'GET')]
    public function fetch(User $user, SerializerInterface $objectSerializer): JsonResponse
    {
        if (false === $user->isEnabled()) {
            return $this->json(['error' => sprintf('%s is disabled', $user->fullName())], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            ['user' => $objectSerializer->serialize(
                $user,
                'json',
                ['groups' => 'presentation']
            )],
            Response::HTTP_OK
        );
    }

    #[Route('/user/disable', name: 'app_user_disable', methods: 'PATCH')]
    public function disable(Request $request, RequestHandlerInterface $disableUserRequestHandler): JsonResponse
    {
        try {
            $disableUserRequestHandler->handle($request);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'User is disabled!'], Response::HTTP_OK);
    }

    #[Route('/user/enable', name: 'app_user_enable', methods: 'PATCH')]
    public function enable(Request $request, RequestHandlerInterface $enableUserRequestHandler): JsonResponse
    {
        try {
            $enableUserRequestHandler->handle($request);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'User is enabled!'], Response::HTTP_OK);
    }

    #[Route('/user/remove/{id}', name: 'app_user_remove', methods: 'DELETE')]
    public function remove(User $user, UserService $userService): JsonResponse
    {
        try {
            $userService->remove($user);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'User is removed!'], Response::HTTP_OK);
    }

    #[Route('/user/create', name: 'app_user_create', methods: 'POST')]
    public function create(Request $request, RequestHandlerInterface $createUserRequestHandler): JsonResponse
    {
        try {
            $createUserRequestHandler->handle($request);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Successfully created the new user!'], Response::HTTP_OK);
    }
}
