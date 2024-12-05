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


    #[Route('/api/messages{id}', name: 'SendMessage', methods: ['POST'])]
    public function sendMessages(int $id, Request $request){
        $data = json_decode($request->getContent(), true);
        $message = $data['content'] ?? '';

        try{
            $send = $this->sendMessage->sendMessages($id, $message);
            $accToken = $send['acc'];
            $message = $send['messages'];
            $response = new JsonResponse($message, 201);
            $response->headers->set('X-Acc-Token', $accToken);
            return $response;
        }catch (\Exception $e){
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }

    #[Route(path: '/api/messages', name: 'GetMessagesService', methods: ['GET'])]
    public function getMessages(Request $request){
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

    #[Route('/api/messages{id}', name: 'DeleteMessages', methods: ['DELETE'])]
    public function deleteMessages(int $id, Request $request){
        try{
            $isDelete = $this->deleteMessage->delete($id);
            $accToken = $isDelete['acc'];
            $message = $isDelete['message'];
            $response = new JsonResponse($message, 201);
            $response->headers->set('X-Acc-Token', $accToken);
            return $response;
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    #[Route('/api/messages{id}', name: 'ChangeMessages', methods: ['PUT'])]
    public function changeMessages(int $id, Request $request){
        $date = json_decode($request->getContent(), true);
        $newMessages = $date['newMessage'] ?? '';

        try{
            $result = $this->changeMessages->changeMessages($id, $newMessages);
            $accToken = $result['acc'];
            $message = $result['messages'];
            $response = new JsonResponse($message, 201);
            $response->headers->set('X-Acc-Token', $accToken);
            return $response;
        }catch (\Exception $e){
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }

    }



}