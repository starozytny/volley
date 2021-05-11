<?php

namespace App\Controller\Api;

use App\Entity\Blog\BoArticle;
use App\Entity\User;
use App\Repository\Blog\BoArticleRepository;
use App\Service\ApiResponse;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

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

    /**
     * Admin - Create an article
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="create", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a new article object",
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="JSON empty or missing data or validation failed",
     * )
     *
     * @OA\RequestBody (
     *     @Model(type=BoArticle::class, groups={"admin:write"}),
     *     required=true
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function create(Request $request, ValidatorService $validator, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());

        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        if (!isset($data->title)) {
            return $apiResponse->apiJsonResponseBadRequest('Il manque des données.');
        }

        $article = new BoArticle();
        $article->setTitle(trim($data->title));
        $article->setIntroduction($data->introduction->html ?: null);
        $article->setContent($data->content->html ?: null);

        $noErrors = $validator->validate($article);

        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $em->persist($article);
        $em->flush();

        return $apiResponse->apiJsonResponse($article, User::ADMIN_READ);
    }
}
