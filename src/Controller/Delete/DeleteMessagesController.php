<?php


namespace App\Controller\Delete;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Delete\DeleteMessageService;
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
            if ($isDelete) {
                return new JsonResponse(['success' => true], 201);
            }
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }
}
