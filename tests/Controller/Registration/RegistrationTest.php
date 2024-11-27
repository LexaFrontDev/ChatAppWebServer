<?php


namespace App\Tests\Controller\Registration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends WebTestCase
{


    public function testBadRequest()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/register');
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }


    public function testResponse()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'password' => 'test1234'
        ]));


        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $headers = $response->headers->all();
        var_dump($headers);
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }
}