<?php


namespace App\Service\JsonResponseService;


use Symfony\Component\HttpFoundation\JsonResponse;

class CreateJsonResponseService
{

    public function createJson($data, $status = 200, $headers = []){
        $response = new JsonResponse($data, $status);
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        return $response;
    }

}