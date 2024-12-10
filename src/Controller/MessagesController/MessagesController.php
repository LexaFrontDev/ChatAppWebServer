<?php


namespace App\Controller\MessagesController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\MessagesService\SendMessagesService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MessagesService\GetMessagesService;
use App\Service\MessagesService\DeleteMessageService;
use App\Service\MessagesService\ChangeMessageService;
use App\Service\JsonResponseService\CreateJsonResponseService;

class MessagesController extends AbstractController
{
    private $getMessagesService;
    private $sendMessage;
    private $deleteMessage;
    private  $changeMessages;
    private $createJsonService;

    public function __construct
    (
        CreateJsonResponseService $createJsonService,
        ChangeMessageService $changeMessages,
        GetMessagesService $getMessagesService,
        SendMessagesService $sendMessage,
        DeleteMessageService $deleteMessage
    )
    {
        $this->createJsonService = $createJsonService;
        $this->changeMessages = $changeMessages;
        $this->deleteMessage = $deleteMessage;
        $this->getMessagesService = $getMessagesService;
        $this->sendMessage = $sendMessage;
    }



    #[Route('/api/messages/users{id}', name: 'SendMessageUsers', methods: ['POST'])]
    public function sendMessagesUsers(int $id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $message = $data['content'] ?? '';

        try {
            $send = $this->sendMessage->sendMessagesUsers($id, $message);
            return $this->createJsonService->createJson($send['messages'], 201, ['X-Acc-Token' => $send['acc']]);
        } catch (\Exception $e) {
            return $this->createJsonService->createJson(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route('/api/messages/group{id}', name: 'SendMessageGroup', methods: ['POST'])]
    public function sendMessagesGroup($id, Request $request){
        $data = json_decode($request->getContent(), true);
        $message = $data['content'] ?? '';

        try {
            $send = $this->sendMessage->sendMessagesGroup($id, $message);
            return $this->createJsonService->createJson($send['messages'], 201, ['X-Acc-Token' => $send['acc']]);
        } catch (\Exception $e) {
            return $this->createJsonService->createJson(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route(path: '/api/messages', name: 'GetMessagesService', methods: ['GET'])]
    public function getMessages(Request $request)
    {
        try {
            $get = $this->getMessagesService->getAllMessages();
            return $this->createJsonService->createJson(['data' => $get['date']], 200, ['X-Acc-Token' => $get['acc']]);
        } catch (\InvalidArgumentException $e) {
            return $this->createJsonService->createJson(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route('/api/messages{id}', name: 'DeleteMessages', methods: ['DELETE'])]
    public function deleteMessages(int $id)
    {
        try {
            $isDelete = $this->deleteMessage->delete($id);
            return $this->createJsonService->createJson($isDelete['message'], 201, ['X-Acc-Token' => $isDelete['acc']]);
        } catch (\Exception $e) {
            return $this->createJsonService->createJson(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route('/api/messages{id}', name: 'ChangeMessages', methods: ['PUT'])]
    public function changeMessages(int $id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $newMessage = $data['newMessage'] ?? '';

        try {
            $result = $this->changeMessages->changeMessages($id, $newMessage);
            return $this->createJsonService->createJson($result['messages'], 201, ['X-Acc-Token' => $result['acc']]);
        } catch (\Exception $e) {
            return $this->createJsonService->createJson(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }
}
