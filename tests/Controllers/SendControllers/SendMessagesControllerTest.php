<?php


namespace App\Tests\Controllers\SendControllers;

use App\Entity\Users;
use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


class SendMessagesControllerTest extends WebTestCase
{

    public function testBadResponse()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/send/message',[], [], [], json_encode([
            'receiver_id' => '',
            'content' => ''
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }

    public function testSendMessagesResponse()
    {
        $client  = $this->createAuthenticatedApiClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $repository = $entityManager->getRepository(Users::class);
        $receiver = $repository->findOneBy(['name' => 'test2']);
        $receiverId = $receiver->getId();

        $client->request('POST', '/api/send/message',[], [], [], json_encode([
            'receiver_id' => $receiverId,
            'content' => 'с новым годом!'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }

}