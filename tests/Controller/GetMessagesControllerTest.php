<?php


namespace App\Tests\Controller;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;



class GetMessagesControllerTest extends WebTestCase
{
    public function testBadResponse()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/getMessages');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }


    public function testGetMessagesResponse()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/getMessages');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
    }
}
