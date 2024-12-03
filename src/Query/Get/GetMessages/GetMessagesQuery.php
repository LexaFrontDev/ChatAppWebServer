<?php


namespace App\Query\Get\GetMessages;


use App\Entity\Users;
use App\Entity\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\MessagesService\EncryptionService;

class GetMessagesQuery
{

    private EncryptionService $encryptService;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EncryptionService $encryptService, EntityManagerInterface $entityManager,Security $security)
    {
        $this->encryptService = $encryptService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function getMessages($receiver)
    {
        $messagesReceived = $this->entityManager->getRepository(Messages::class)
            ->findBy(['receiver' => $receiver]);

        $messagesSent = $this->entityManager->getRepository(Messages::class)
            ->findBy(['sender' => $receiver]);

        $result = [
            'receivedMessages' => [],
            'sentMessages' => [],
        ];

        foreach ($messagesReceived as $message) {
            $result['receivedMessages'][] = $this->formatMessage($message, 'sender', 'receiver');
        }

        foreach ($messagesSent as $message) {
            $result['sentMessages'][] = $this->formatMessage($message, 'receiver', 'sender');
        }

        return $result;
    }

    private function formatMessage($message, $counterPartyRole,  $selfRole)
    {
        $counterParty = $counterPartyRole === 'sender'
            ? $message->getSender()
            : $message->getReceiver();

        $self = $selfRole === 'sender'
            ? $message->getSender()
            : $message->getReceiver();

        $content = $message->getContent();
        $iv = $message->getIv();
        $decryptedMessage = $this->encryptService->decryptMessage($content, $iv);

        return [
            'id' => $message->getId(),
            'message' => $decryptedMessage,
            'timeSent' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
            $counterPartyRole => [
                'id' => $counterParty ? $counterParty->getId() : null,
                'email' => $counterParty ? $counterParty->getEmail() : null,
                'name' => $counterParty ? $counterParty->getName() : null,
            ],
            $selfRole => [
                'id' => $self ? $self->getId() : null,
                'email' => $self ? $self->getEmail() : null,
                'name' => $self ? $self->getName() : null,
            ],
        ];
    }

}