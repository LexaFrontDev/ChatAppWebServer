<?php


namespace App\Command\Create\CreateMessages;


interface  createMessageInterfaceCommand
{

    public function createMessages($sender, $receiver, string $encryptedContent, string $iv);

}