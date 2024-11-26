<?php


namespace App\Tests\AplicationTests\testMessages;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetMessagesTest extends WebTestCase
{


    public function testGetMessage()
    {
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzI2MTUzMDgsImV4cCI6MTczMjYxODkwOCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiTGV4YURldjEwQGdtYWlsLmNvbSJ9.KhvlZIZbdzCJq5GnEUoe6sWWOIb43eHAoVZQucGjOV5CJAzz_h7-aKCfUdO0e4p5pZUPmpVj4ozv5z81xNirG02j9K7Ynf--S7q5kXDLEZfe4IvvYkiAeTvOQ3j5W-Rn8iBfsBYoUJrkKHVxvRLDkhcLnJ2t7D8ktvXVwGlC0w_rCyXJ8GKkpjTgkdMT6OeNfsjaRA88q68Asl5yLAWkv_fwU_DxQ5D5aghIQRbk98uM0CROdnLywla-su7duQzCixxvm9amWyHlSiAehTcM5P23a3jR4aD9xyg920w_Nu_p8S_jrqR1BYYjPZobR4H6O77kF19m__ouZF-QkegIHw";

        $client = static::createClient();
        $crawler = $client->request('POST', '/api/getMessages', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode(), 'Expected status code 201');
        $content = $response->getContent();
        $this->assertJson($content, 'Response is not valid JSON');
    }

}