<?php

namespace App\Controller\Api;

use App\Entity\Blog\BoArticle;
use App\Entity\User;
use App\Repository\Blog\BoArticleRepository;
use App\Service\ApiResponse;
use App\Service\FileUploader;
use App\Service\SanitizeData;
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
     * @param FileUploader $fileUploader
     * @return JsonResponse
     */
    public function create(Request $request, ValidatorService $validator, ApiResponse $apiResponse, FileUploader $fileUploader): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $file = $request->files->get('file');

        $fileName = ($file) ? $fileUploader->upload($file, "articles", true) : null;
        $title = $request->get('title');
        $introduction = $request->get('introduction');
        $content = $request->get('content');

        $article = new BoArticle();
        $article->setTitle(trim($title));
        $article->setIntroduction($introduction ?: null);
        $article->setContent($content ?: null);
        $article->setFile($fileName);

        $noErrors = $validator->validate($article);

        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $em->persist($article);
        $em->flush();

        return $apiResponse->apiJsonResponse($article, User::ADMIN_READ);
    }

    /**
     * Update an article
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/{id}", name="update", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns an article object",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or article",
     * )
     * @OA\Response(
     *     response=400,
     *     description="Validation failed",
     * )
     *
     * @OA\RequestBody (
     *     description="Only admin can change roles",
     *     @Model(type=BoArticle::class, groups={"admin:write"}),
     *     required=true
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param ApiResponse $apiResponse
     * @param BoArticle $article
     * @param FileUploader $fileUploader
     * @return JsonResponse
     */
    public function update(Request $request, ValidatorService $validator, ApiResponse $apiResponse, BoArticle $article, FileUploader $fileUploader): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $file = $request->files->get('file');

        if($file){
            $oldFile = $this->getParameter('public_directory'). 'articles/' . $article->getFile();
            if($article->getFile() && file_exists($oldFile)){
                unlink($oldFile);
            }

            $fileName = $fileUploader->upload($file, "articles", true);
            $article->setFile($fileName);
        }
        $title = $request->get('title');
        $introduction = $request->get('introduction');
        $content = $request->get('content');

        $article->setTitle(trim($title));
        $article->setIntroduction($introduction ?: null);
        $article->setContent($content ?: null);
        $updatedAt = new \DateTime();
        $updatedAt->setTimezone(new \DateTimeZone("Europe/Paris"));
        $article->setUpdatedAt($updatedAt);

        $noErrors = $validator->validate($article);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $em->persist($article);
        $em->flush();

        return $apiResponse->apiJsonResponse($article, User::ADMIN_READ);
    }
}
