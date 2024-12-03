<?php


namespace App\Validation\MessagesValidate;

use App\Service\AuthService\TokenService;

class GetMessagesValidator
{
    private TokenService $accToken;

    public function __construct(TokenService $accToken)
    {
        $this->accToken = $accToken;
    }


    public function validate($receiver, $result)
    {
        $accToken = $this->accToken->createToken($receiver);
        $receivedMess = $result['receivedMessages'];
        $sentMess = $result['sentMessages'];

        if(empty($receivedMess) && !empty($sentMess)){
            return ['acc' => $accToken, 'date' => $result['sentMessages']];
        }
        if(!empty($receivedMess) && empty($sentMess)){
            return ['acc' => $accToken, 'date' => $result['receivedMessages']];
        }

        if(empty($receivedMess) || empty($sentMess)){
            return ['acc' => $accToken, 'date' => 'У вас нет активных чатов'];
        }

    }


}