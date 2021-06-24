<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\NotificationRepository;
use App\Service\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/notifications", name="api_notifications_")
 */
class NotificationController extends AbstractController
{
    /**
     * Get array of notifications
     *
     * @Route("/", name="index", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns array of notifications",
     * )
     * @OA\Tag(name="Contact")
     *
     * @param NotificationRepository $repository
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function index(NotificationRepository $repository, ApiResponse $apiResponse): JsonResponse
    {
        $objs = $repository->findAll();
        return $apiResponse->apiJsonResponse($objs, User::ADMIN_READ);
    }
}
