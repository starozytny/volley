<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ApiResponse;
use App\Service\SanitizeData;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/", name="index", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns array of users",
     *     @Model(type=User::class, groups={"admin:read"})
     * )
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function index(Request $request, UserRepository $userRepository, ApiResponse $apiResponse): JsonResponse
    {
        $orderUsername = $request->query->get('orderUsername') ?: 'ASC';
        $users = $userRepository->findBy([], ['username' => $orderUsername]);
        return $apiResponse->apiJsonResponse($users, User::ADMIN_READ);
    }

    /**
     * Admin - Create an user
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="create", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a new user object",
     *     @Model(type=User::class, groups={"admin:write"})
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="JSON empty or missing data or validation failed",
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
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ApiResponse $apiResponse
     * @param SanitizeData $sanitizeData
     * @return JsonResponse
     */
    public function create(Request $request, ValidatorService $validator, UserPasswordEncoderInterface $passwordEncoder,
                           ApiResponse $apiResponse, SanitizeData $sanitizeData): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());

        if($data === null){
            return new JsonResponse(['message' => 'Les données sont vides.'], 400);
        }

        if(!isset($data->username) || !isset($data->email) || !isset($data->password)){
            return new JsonResponse(['message' => 'Il manque des données.'], 400);
        }

        $user = new User();
        $user->setUsername($sanitizeData->fullSanitize($data->username));
        $user->setEmail($data->email);
        $user->setPassword($passwordEncoder->encodePassword($user, $data->password));

        if(isset($data->roles)){
            $user->setRoles($data->roles);
        }

        $noErrors = $validator->validate($user);

        if($noErrors !== true){
            return new JsonResponse($noErrors, 400);
        }

        $em->persist($user);
        $em->flush();

        return $apiResponse->apiJsonResponse($user, User::ADMIN_READ);
    }

    /**
     * Admin - Update an user
     *
     * @Route("/{id}", name="update", options={"expose"=true}, methods={"PUT"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns an user object",
     *     @Model(type=User::class, groups={"update"})
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Validation failed",
     * )
     *
     * @OA\RequestBody (
     *     description="Only admin can change roles",
     *     @Model(type=User::class, groups={"update"}),
     *     required=true
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param ApiResponse $apiResponse
     * @param SanitizeData $sanitizeData
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, ValidatorService $validator,
                           ApiResponse $apiResponse, SanitizeData $sanitizeData, User $user): JsonResponse
    {
        if($this->getUser() != $user && !$this->isGranted("ROLE_ADMIN") ){
            return new JsonResponse(['message' => 'Vous n\'êtes pas autorisé à accéder à cette page.'], 403);
        }

        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());

        if(isset($data->username)){
            $user->setUsername($sanitizeData->fullSanitize($data->username));
        }

        if(isset($data->email)){
            $user->setEmail($data->email);
        }

        $groups = User::ADMIN_READ;
        if($this->isGranted("ROLE_ADMIN")){
            if(isset($data->roles)){
                $user->setRoles($data->roles);
            }
            $groups = User::USER_READ;
        }

        $noErrors = $validator->validate($user);

        if($noErrors !== true){
            return new JsonResponse($noErrors, 400);
        }

        $em->persist($user);
        $em->flush();

        return $apiResponse->apiJsonResponse($user, $groups);
    }
}
