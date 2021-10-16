<?php

namespace App\Controller\Api\Volley;

use App\Entity\App\Volley\VoMatch;
use App\Entity\User;
use App\Service\ApiResponse;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/volley", name="api_matchs_")
 */
class MatchController extends AbstractController
{
    public function setMatch(VoMatch $obj, $request)
    {
        $startAt = $request->get('startAt');
        $team = $request->get('team');
        $versus = $request->get('versus');
        $localisation = $request->get('localisation');

        $startAt = date_create_from_format('Y-m-d', $startAt);
        $startAt->setTimezone(new \DateTimeZone("Europe/Paris"));

        $obj->setStartAt($startAt);
        $obj->setTeam(trim($team));
        $obj->setVersus(trim($versus));
        $obj->setLocalisation($localisation);

        return $obj;
    }

    /**
     * Admin - Create a match
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/matchs", name="create", options={"expose"=true}, methods={"POST"})
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
     * @OA\Tag(name="Volley")
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function create(Request $request, ValidatorService $validator, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $obj = $this->setMatch(new VoMatch(), $request);

        if($obj instanceof VoMatch){
            $noErrors = $validator->validate($obj);
            if ($noErrors !== true) {
                return $apiResponse->apiJsonResponseValidationFailed($noErrors);
            }

            $em->persist($obj);
            $em->flush();
            return $apiResponse->apiJsonResponse($obj, User::VISITOR_READ);
        }else{
            return $apiResponse->apiJsonResponseBadRequest("Erreur dans la création du match");
        }
    }

    /**
     * Update a match
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/matches/{id}", name="update", options={"expose"=true}, methods={"POST"})
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
     * @OA\Tag(name="Volley")
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param ApiResponse $apiResponse
     * @param VoMatch $obj
     * @return JsonResponse
     */
    public function update(Request $request, ValidatorService $validator, ApiResponse $apiResponse, VoMatch $obj): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $obj = $this->setMatch($obj, $request);

        if($obj instanceof VoMatch){
            $noErrors = $validator->validate($obj);
            if ($noErrors !== true) {
                return $apiResponse->apiJsonResponseValidationFailed($noErrors);
            }

            $em->flush();
            return $apiResponse->apiJsonResponse($obj, User::VISITOR_READ);
        }else{
            return $apiResponse->apiJsonResponseBadRequest("Erreur dans la mise à jour du match");
        }
    }

    /**
     * Admin - Delete a match
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/matches/{id}", name="delete", options={"expose"=true}, methods={"DELETE"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or article",
     * )
     *
     * @OA\Tag(name="Volley")
     *
     * @param ApiResponse $apiResponse
     * @param VoMatch $obj
     * @return JsonResponse
     */
    public function delete(ApiResponse $apiResponse, VoMatch $obj): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($obj);
        $em->flush();

        return $apiResponse->apiJsonResponseSuccessful("Supression réussie !");
    }

    /**
     * Admin - Delete a group of match
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="delete_group", options={"expose"=true}, methods={"DELETE"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or articles",
     * )
     *
     * @OA\Tag(name="Volley")
     *
     * @param Request $request
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function deleteGroup(Request $request, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());

        $objs = $em->getRepository(VoMatch::class)->findBy(['id' => $data]);

        if ($objs) {
            foreach ($objs as $obj) {
                $em->remove($obj);
            }
        }

        $em->flush();
        return $apiResponse->apiJsonResponseSuccessful("Supression de la sélection réussie !");
    }
}
