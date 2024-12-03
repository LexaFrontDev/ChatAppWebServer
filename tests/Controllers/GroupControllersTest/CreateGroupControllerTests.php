<?php


namespace App\Tests\Controllers\GroupControllersTest;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class CreateGroupControllerTests extends WebTestCase
{

    public function testBadResponse()
    {
        $client  = $this->createAnonymousApiClient();
        $client->request('POST', '/api/create/group',[], [], [], json_encode([
            'groupName' => 'testGroup2'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }

    public function testCreateGroupController(){
        $client  = $this->createAuthenticatedApiClient();

        $client->request('POST', '/api/create/group',[], [], [], json_encode([
            'groupName' => 'testGroup2'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }
}