<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetMessagesTest extends WebTestCase
{
    public function testGetMessagesResponse()
    {
        $client = $this->createClient();

        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'password' => 'test1234'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = $response->headers->all();
//        var_dump($data);
        $accToken = $data['x-acc-token'][0];



        $client->request('POST', '/api/getMessages', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $accToken,
        ]);


        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $headers = $response->headers->all();
        var_dump($headers);
        $data = json_decode($response->getContent(), true);
        var_dump($data);
        $this->assertIsArray($data);
    }
}
