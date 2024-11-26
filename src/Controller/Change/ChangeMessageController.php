<?php


namespace App\Controller\Change;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Change\ChangeMessageService;

class ChangeMessageController extends AbstractController
{

    private ChangeMessageService $changeMessages;


    public function __construct(ChangeMessageService $changeMessages)
    {
        $this->changeMessages = $changeMessages;
    }


    #[Route('/api/change/messages', name: 'ChangeMessages', methods: ['POST'])]
    public function changeMess(Request $request)
    {
        $date = json_decode($request->getContent(), true);
        $messageID = $date['messageID'] ?? '';
        $newMessages = $date['newMessage'] ?? '';

        try{
            $result = $this->changeMessages->changeMessages($messageID, $newMessages);
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