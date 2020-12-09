<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ApiResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="index", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns array of users",
     *     @Model(type=User::class, groups={"user:read"})
     * )
     * @OA\Tag(name="Users")
     *
     * @param UserRepository $userRepository
     * @param ApiResponse $apiResponse
     * @return Response
     */
    public function index(UserRepository $userRepository, ApiResponse $apiResponse): Response
    {
        $users = $userRepository->findAll();
        return $apiResponse->apiJsonResponse($users, ['user:read']);
    }


}
