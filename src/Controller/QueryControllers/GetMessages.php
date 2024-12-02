<?php


namespace App\Controller\QueryControllers;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use App\Service\GetMessagesService;

class GetMessages extends AbstractController
{
    private $getMessagesService;

    public function __construct(GetMessagesService $getMessagesService)
    {
        $this->getMessagesService = $getMessagesService;
    }

    #[Route(path: '/api/get/messages', name: 'GetMessagesService', methods: ['GET'])]
    public function get(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try{
            $get = $this->getMessagesService->getAllMessages();
            $accToken = $get['acc'];
            $date = $get['date'];
            $response = new JsonResponse($get, 200);
            $response->headers->set('X-Acc-Token', $accToken);
            $response->setData(['data' => $date]);
            return $response;
        }catch (\InvalidArgumentException $e)
        {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }
}