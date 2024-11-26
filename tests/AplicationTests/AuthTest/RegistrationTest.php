<?php


namespace App\Tests\AplicationTests\AuthTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends WebTestCase
{
    public function testRegisterWithDatabaseInteraction()
    {
        $name = "LexaDev15";
        $email = "LexaDev15@gmail.com";
        $password = "LexaDev1234";
        $client = static::createClient();

        $data = json_encode([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $crawler = $client->request('POST', '/api/register', [], [], [
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
