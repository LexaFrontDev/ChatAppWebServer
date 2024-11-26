<?php


namespace App\Tests\AplicationTests\AuthTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends WebTestCase
{
    public function testRegisterWithDatabaseInteraction()
    {
        $name = "LexaDev10";
        $email = "LexaDev10@gmail.com";
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
        $responseHeaders = $response->headers->all();
        echo "Response Headers:\n";
        var_dump($responseHeaders);
        $content = $client->getResponse()->getContent();
        $this->assertJson($content);
        $data = json_decode($content, true);
        $this->assertIsArray($data);
    }
}
