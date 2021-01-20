<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\SettingsRepository;
use App\Service\ApiResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/settings", name="api_settings_")
 */
class SettingsController extends AbstractController
{
    /**
     * Get settings data
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="index", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns settings",
     * )
     * @OA\Tag(name="Settings")
     *
     * @param ApiResponse $apiResponse
     * @param SettingsRepository $repository
     * @return JsonResponse
     */
    public function index(ApiResponse $apiResponse, SettingsRepository $repository): JsonResponse
    {
        $settings = $repository->findAll();
        if($settings){
            $settings = $settings[0];
        }
        return $apiResponse->apiJsonResponse($settings, User::VISITOR_READ);
    }
}
