<?php


namespace App\Tests\Controller\Registration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{


    public function testBadRequestRegisterController()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/register');
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }


    public function testResponseRegisterController()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'test1234'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
    }
}