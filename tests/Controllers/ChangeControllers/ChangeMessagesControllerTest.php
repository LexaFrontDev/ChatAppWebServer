<?php


namespace App\Tests\Controllers\ChangeControllers;


use App\Entity\Messages;
use App\Entity\Users;
use App\Tests\TestCase\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ChangeMessagesControllerTest extends WebTestCase
{


    public function testBadResponse()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/change/messages',[], [], [], json_encode([
            'messageID' => '1',
            'newMessage' => 'Новый год еще не наступил:('
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }

    public function testResponseChangeMessagesController()
    {
        $client  = $this->createAuthenticatedApiClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $repositoryUsers = $entityManager->getRepository(Users::class);
        $repositoryMessages = $entityManager->getRepository(Messages::class);
        $sender = $repositoryUsers->findOneBy(['name' => 'test1']);
        $senderId = $sender->getId();
        $messages = $repositoryMessages->findOneBy(['sender' => $senderId]);
        $messagesId = $messages->getId();

        $client->request('POST', '/api/change/messages',[], [], [], json_encode([
            'messageID' => $messagesId,
            'newMessage' => 'Новый год еще не наступил:('
            ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }
}