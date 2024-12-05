<?php


namespace App\Tests\Controllers\MessagesControllerTest;

use App\Entity\Users;
use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


class SendMessagesControllerTest extends WebTestCase
{

    public function testBadResponse()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/messages3',[], [], [], json_encode([
            'content' => ''
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }

    public function testSendMessagesResponse()
    {
        $client  = $this->createAuthenticatedApiClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $repository = $entityManager->getRepository(Users::class);
        $receiver = $repository->findOneBy(['name' => 'test2']);
        $receiverId = $receiver->getId();

        $client->request('POST', '/api/messages' . $receiverId,[], [], [], json_encode([
            'content' => 'с новым годом!'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }

}