<?php


namespace App\Controller;



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

    #[Route(path: '/api/getMessages', name: 'GetMessagesService', methods: ['POST'])]
    public function get(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['receiver_id'] ?? '';

        try{
            $get = $this->getMessagesService->getAllMessage($id);
            return new JsonResponse($get, 201);
        }catch (\InvalidArgumentException $e)
        {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }
}