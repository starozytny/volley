<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ApiResponse;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractController
{
    /**
     * Admin - Get array of users
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="index", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns array of users",
     *     @Model(type=User::class, groups={"admin:read"})
     * )
     * @OA\Tag(name="Users")
     *
     * @param UserRepository $userRepository
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function index(UserRepository $userRepository, ApiResponse $apiResponse): JsonResponse
    {
        $users = $userRepository->findAll();
        return $apiResponse->apiJsonResponse($users, ['admin:read']);
    }

    /**
     * Admin - Create an user
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="create", methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a new user object",
     *     @Model(type=User::class, groups={"admin:write"})
     * )
     *
     * @OA\RequestBody (
     *     @Model(type=User::class, groups={"admin:write"}),
     *     required=true
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @return JsonResponse
     */
    public function create(Request $request, ValidatorService $validator): JsonResponse
    {
        $data = json_decode($request->getContent());

        $user = new User();
        $user->setUsername($data->username);
        $user->setEmail($data->email);
        $user->setPlainPassword($data->password);

        $noErrors = $validator->validate($user);

        if($noErrors !== true){
            return new JsonResponse($noErrors, 400);
        }

        return new JsonResponse("a", 200);
    }
}
