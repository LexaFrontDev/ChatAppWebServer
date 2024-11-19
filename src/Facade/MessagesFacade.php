<?php


namespace App\Facade;

use App\Entity\Messages;
use App\Singleton\EntityManagerSingleton;


class MessagesFacade
{

    private $entityManagerSingleton;


    public function __construct(EntityManagerSingleton $entityManagerSingleton)
    {
        $this->entityManagerSingleton = $entityManagerSingleton;
    }



    public function createMessages($sender, $receiver, $content){
        $message = new Messages();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($content);
        $this->entityManagerSingleton->save($message);
        return $this;
    }


}