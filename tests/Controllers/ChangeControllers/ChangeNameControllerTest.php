<?php


namespace App\Tests\Controllers\ChangeControllers;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class ChangeNameControllerTest extends WebTestCase
{
    public function testBadResponse()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/name');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }

    public function testResponseChangeName()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/name',[], [], [], json_encode(['newName' => 'testChangeName',]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }

}