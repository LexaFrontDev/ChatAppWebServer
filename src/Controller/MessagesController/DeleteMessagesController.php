<?php


namespace App\Controller\MessagesController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MessagesService\DeleteMessageService;
use Symfony\Component\Routing\Annotation\Route;

class DeleteMessagesController extends AbstractController
{
    private DeleteMessageService $deleteMessage;

    public function __construct(DeleteMessageService $deleteMessage)
    {
        $this->deleteMessage = $deleteMessage;
    }

    #[Route('/api/delete/message', name: 'DeleteMessages', methods: ['POST'])]
    public function deleteMess(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $messageId = $data['messageID'] ?? '';

        if (empty($messageId)) {
            return new JsonResponse(['error' => 'messageID is required'], 400);
        }

        try {
            $isDelete = $this->deleteMessage->delete($messageId);
            $accToken = $isDelete['acc'];
            $message = $isDelete['message'];
            $response = new JsonResponse($message, 201);
            $response->headers->set('X-Acc-Token', $accToken);
            return $response;
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }
}
