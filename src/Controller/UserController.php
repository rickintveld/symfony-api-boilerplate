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
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/user/all', name: 'app_users', methods: 'GET')]
    public function all(SerializerInterface $objectSerializer): JsonResponse
    {
        return $this->json([
            'users' => $objectSerializer->serialize($this->userService->findAll(), 'json')
        ], Response::HTTP_OK);
    }

    #[Route('/user/{id}', name: 'app_user', methods: 'GET')]
    public function fetch(User $user, SerializerInterface $objectSerializer): JsonResponse
    {
        return $this->json(['user' => $objectSerializer->serialize($user, 'json')], Response::HTTP_OK);
    }

    #[Route('/user/disable', name: 'app_user_disable', methods: 'PATCH')]
    public function disable(Request $request, RequestHandlerInterface $disableUserRequestHandler): JsonResponse
    {
        try {
            $disableUserRequestHandler->handle($request);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'User is disabled!'], Response::HTTP_OK);
    }

    #[Route('/user/enable', name: 'app_user_enable', methods: 'PATCH')]
    public function enable(Request $request, RequestHandlerInterface $enableUserRequestHandler): JsonResponse
    {
        try {
            $enableUserRequestHandler->handle($request);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'User is enabled!'], Response::HTTP_OK);
    }

    #[Route('/user/remove/{id}', name: 'app_user_remove', methods: 'DELETE')]
    public function remove(User $user): JsonResponse
    {
        $this->userService->remove($user);

        return $this->json(['message' => 'User is removed!'], Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/create', name: 'app_user_create', methods: 'POST')]
    public function create(Request $request, RequestHandlerInterface $createUserRequestHandler): JsonResponse
    {
        try {
            $createUserRequestHandler->handle($request);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Successfully created the new user'], Response::HTTP_OK);
    }
}
