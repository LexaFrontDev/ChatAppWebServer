<?php


namespace App\Controller\MessagesController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\MessagesService\SendMessagesService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MessagesService\GetMessagesService;
use App\Service\MessagesService\DeleteMessageService;
use App\Service\MessagesService\ChangeMessageService;

class MessagesController extends AbstractController
{
    private $getMessagesService;
    private $sendMessage;
    private $deleteMessage;
    private  $changeMessages;

    public function __construct
    (
        ChangeMessageService $changeMessages,
        GetMessagesService $getMessagesService,
        SendMessagesService $sendMessage,
        DeleteMessageService $deleteMessage
    )
    {
        $this->changeMessages = $changeMessages;
        $this->deleteMessage = $deleteMessage;
        $this->getMessagesService = $getMessagesService;
        $this->sendMessage = $sendMessage;
    }

    private function createJsonResponse($data, $status = 200, $headers = [])
    {
        $response = new JsonResponse($data, $status);
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        return $response;
    }

    #[Route('/api/messages{id}', name: 'SendMessage', methods: ['POST'])]
    public function sendMessages(int $id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $message = $data['content'] ?? '';

        try {
            $send = $this->sendMessage->sendMessages($id, $message);
            return $this->createJsonResponse($send['messages'], 201, ['X-Acc-Token' => $send['acc']]);
        } catch (\Exception $e) {
            return $this->createJsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route(path: '/api/messages', name: 'GetMessagesService', methods: ['GET'])]
    public function getMessages(Request $request)
    {
        try {
            $get = $this->getMessagesService->getAllMessages();
            return $this->createJsonResponse(['data' => $get['date']], 200, ['X-Acc-Token' => $get['acc']]);
        } catch (\InvalidArgumentException $e) {
            return $this->createJsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route('/api/messages{id}', name: 'DeleteMessages', methods: ['DELETE'])]
    public function deleteMessages(int $id)
    {
        try {
            $isDelete = $this->deleteMessage->delete($id);
            return $this->createJsonResponse($isDelete['message'], 201, ['X-Acc-Token' => $isDelete['acc']]);
        } catch (\Exception $e) {
            return $this->createJsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route('/api/messages{id}', name: 'ChangeMessages', methods: ['PUT'])]
    public function changeMessages(int $id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $newMessage = $data['newMessage'] ?? '';

        try {
            $result = $this->changeMessages->changeMessages($id, $newMessage);
            return $this->createJsonResponse($result['messages'], 201, ['X-Acc-Token' => $result['acc']]);
        } catch (\Exception $e) {
            return $this->createJsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }
}
