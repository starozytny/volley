<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use App\Service\ApiResponse;
use App\Service\Export;
use App\Service\MailerService;
use App\Service\SettingsService;
use App\Service\ValidatorService;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/contact", name="api_contact_")
 */
class ContactController extends AbstractController
{
    /**
     * Admin - Get array of contacts
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/", name="index", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns array of contacts",
     * )
     * @OA\Tag(name="Contact")
     *
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function index(Request $request, ContactRepository $contactRepository, ApiResponse $apiResponse): JsonResponse
    {
        $order = $request->query->get('order') ?: 'ASC';
        $contacts = $contactRepository->findBy([], ['createdAt' => $order]);
        return $apiResponse->apiJsonResponse($contacts, User::ADMIN_READ);
    }

    /**
     * Admin - Create an message contact
     *
     * @Route("/", name="create", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a message",
     * )
     *
     * @OA\Tag(name="Contact")
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


        if (!isset($data->name) || !isset($data->email) || !isset($data->message)) {
            return $apiResponse->apiJsonResponseBadRequest('Il manque des données.');
        }

        $contact = (new Contact())
            ->setName(trim($data->name))
            ->setEmail($data->email)
            ->setMessage($data->message)
        ;

        $noErrors = $validator->validate($contact);

        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $em->persist($contact);
        $em->flush();

        return $apiResponse->apiJsonResponseSuccessful("Message envoyé.");
    }

    /**
     * Admin - Delete an user
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/{id}", name="delete", options={"expose"=true}, methods={"DELETE"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Cannot delete me",
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param ApiResponse $apiResponse
     * @param User $user
     * @return JsonResponse
     */
    public function delete(ApiResponse $apiResponse, User $user): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        if ($user->getHighRoleCode() === User::CODE_ROLE_DEVELOPER) {
            return $apiResponse->apiJsonResponseForbidden();
        }

        if ($user === $this->getUser()) {
            return $apiResponse->apiJsonResponseBadRequest('Vous ne pouvez pas vous supprimer.');
        }

        $em->remove($user);
        $em->flush();

        return $apiResponse->apiJsonResponseSuccessful("Supression réussie !");
    }

    /**
     * Admin - Delete a group of user
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
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Cannot delete me",
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function deleteGroup(Request $request, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());

        $users = $em->getRepository(User::class)->findBy(['id' => $data]);

        if ($users) {
            foreach ($users as $user) {
                if ($user->getHighRoleCode() === User::CODE_ROLE_DEVELOPER) {
                    return $apiResponse->apiJsonResponseForbidden();
                }

                if ($user === $this->getUser()) {
                    return $apiResponse->apiJsonResponseBadRequest('Vous ne pouvez pas vous supprimer.');
                }

                $em->remove($user);
            }
        }

        $em->flush();
        return $apiResponse->apiJsonResponseSuccessful("Supression de la sélection réussie !");
    }

    /**
     * Forget password
     *
     * @Route("/password/forget", name="password_forget", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param ApiResponse $apiResponse
     * @param MailerService $mailerService
     * @param SettingsService $settingsService
     * @return JsonResponse
     */
    public function passwordForget(Request $request, ApiResponse $apiResponse, MailerService $mailerService, SettingsService $settingsService): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());

        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest("Il manque des données.");
        }

        $username = $data->fUsername;

        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        if (!$user) {
            return $apiResponse->apiJsonResponseValidationFailed([[
                'name' => 'fUsername',
                'message' => "Cet utilisateur n'existe pas."
            ]]);
        }

        if ($user->getForgetAt()) {
            $interval = date_diff($user->getForgetAt(), new DateTime());
            if ($interval->i < 30) {
                return $apiResponse->apiJsonResponseValidationFailed([[
                    'name' => 'fUsername',
                    'message' => "Un lien a déjà été envoyé. Veuillez réessayer ultérieurement."
                ]]);
            }
        }

        $code = uniqid($user->getId());

        $user->setForgetAt(new DateTime());
        $user->setForgetCode($code);

        $url = $this->generateUrl('app_password_reinit', ['token' => $user->getToken(), 'code' => $code], UrlGeneratorInterface::ABSOLUTE_URL);
        if($mailerService->sendMail(
                $user->getEmail(),
                "Mot de passe oublié pour le site " . $settingsService->getWebsiteName(),
                "Lien de réinitialisation de mot de passe.",
                'app/email/security/forget.html.twig',
                ['url' => $url, 'user' => $user, 'settings' => $settingsService->getSettings()]) != true)
        {
            return $apiResponse->apiJsonResponseValidationFailed([[
                'name' => 'fUsername',
                'message' => "Le message n\'a pas pu être délivré. Veuillez contacter le support."
            ]]);
        }

        $em->flush();
        return $apiResponse->apiJsonResponseSuccessful(sprintf("Le lien de réinitialisation de votre mot de passe a été envoyé à : %s", $user->getHiddenEmail()));
    }

    /**
     * Update password
     *
     * @Route("/password/update/{token}", name="password_update", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a message",
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Request $request
     * @param $token
     * @param ValidatorService $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function passwordUpdate(Request $request, $token, ValidatorService $validator, UserPasswordEncoderInterface $passwordEncoder,
                           ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());

        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        $user = $em->getRepository(User::class)->findOneBy(['token' => $token]);
        $user->setPassword($passwordEncoder->encodePassword($user, $data->password));
        $user->setForgetAt(null);
        $user->setForgetCode(null);

        $noErrors = $validator->validate($user);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $em->flush();
        return $apiResponse->apiJsonResponseSuccessful("Modification réalisée avec success ! La page va se rafraichir automatiquement dans 5 secondes.");
    }

    /**
     * Export list users
     *
     * @Route("/export/{format}", name="export", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a new user object",
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @param Export $export
     * @param $format
     * @return BinaryFileResponse
     */
    public function export(Export $export, $format): BinaryFileResponse
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findBy(array(), array('username' => 'ASC'));
        $data = array();

        foreach ($users as $user) {
            $tmp = array(
                $user->getId(),
                $user->getUsername(),
                $user->getHighRole(),
                $user->getEmail(),
                date_format($user->getCreatedAt(), 'd/m/Y'),
            );
            if(!in_array($tmp, $data)){
                array_push($data, $tmp);
            }
        }

        if($format == 'excel'){
            $fileName = 'utilisateurs.xlsx';
            $header = array(array('ID', 'Nom utilisateur', 'Role', 'Email', 'Date de creation'));
        }else{
            $fileName = 'utilisateurs.csv';
            $header = array(array('id', 'username', 'role', 'email', 'createAt'));

            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
        }

        $export->createFile($format, 'Liste des utilisateurs', $fileName , $header, $data, 5, 'export/');
        return new BinaryFileResponse($this->getParameter('private_directory'). 'export/' . $fileName);
    }
}
