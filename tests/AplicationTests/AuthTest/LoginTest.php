<?php


namespace App\Tests\AplicationTests\AuthTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{

    public function testLogin()
    {
        $name = "LexaDev1";
        $email = "LexaDev1@gmail.com";
        $password = "LexaDev1234";
        $client = static::createClient();

        $data = json_encode([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);


        $crawler = $client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], $data);

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode(), 'Expected status code 201');
        $content = $response->getContent();
        $this->assertJson($content, 'Response is not valid JSON');
        $data = json_decode($content, true);
        $this->assertIsArray($data);
    }

}