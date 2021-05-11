<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\Blog\BoArticleRepository;
use App\Repository\UserRepository;
use App\Service\ApiResponse;
use App\Service\Export;
use App\Service\MailerService;
use App\Service\SanitizeData;
use App\Service\SettingsService;
use App\Service\ValidatorService;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/articles", name="api_articles_")
 */
class ArticleController extends AbstractController
{
    /**
     * Admin - Get array of articles
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="index", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns array of articles",
     * )
     * @OA\Tag(name="Articles")
     *
     * @param Request $request
     * @param BoArticleRepository $repository
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function index(Request $request, BoArticleRepository $repository, ApiResponse $apiResponse): JsonResponse
    {
        $order = $request->query->get('order') ?: 'ASC';
        $articles = $repository->findBy([], ['createdAt' => $order]);
        return $apiResponse->apiJsonResponse($articles, User::ADMIN_READ);
    }
}
