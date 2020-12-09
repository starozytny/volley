<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ApiResponse
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    public function apiJsonResponse($data, $groups = [], $code = 200)
    {
        $data = $this->serializer->serialize($data, "json", ['groups' => $groups]);

        $response = new JsonResponse();
        $response->setContent($data);
        $response->setStatusCode($code);

        return $response;
    }
}