<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns array of users",
     * )
     * @OA\Tag(name="Users")
     */
    public function index(): Response
    {
        return new JsonResponse(['code'], 200);
    }
}
