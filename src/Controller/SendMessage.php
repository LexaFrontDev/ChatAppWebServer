<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use App\Service\SendMessagesService;



class SendMessage extends AbstractController
{

    private $sendMessage;

    public function __construct(SendMessagesService $sendMessage)
    {

        $this->sendMessage = $sendMessage;
    }


    #[Route('/api/sendMessage', name: 'SendMessage', methods: ['POST'])]
    public function sendMes(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $idSender = $data['sender_id'] ?? '';
        $idReceiver = $data['receiver_id'] ?? '';
        $message = $data['content'] ?? '';


        try{
            $send = $this->sendMessage->sendMessages($idSender, $idReceiver, $message);
            return new JsonResponse('Вы успешно отправили сообщение', 201);
        }catch (\InvalidArgumentException $e)
        {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }

}