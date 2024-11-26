<?php


namespace App\Tests\AplicationTests\testMessages;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SendMessageTest extends WebTestCase
{

    public function testSendMessage()
    {
        $receiverId =  1;
        $content = 'С новым годом!';
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzI2MzU5OTEsImV4cCI6MTczMjYzOTU5MSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiTGV4YURldjdAZ21haWwuY29tIn0.dZoPwKXOq9oWHurgVAZCoU7oPrYdH8keWldHMFyNpP9H6RQcVW6DPUfjc9Y91s_BHTdGV9WWF-24lbOlf0xcK4-ZKFzmrriDzz_l1VXgs_wOGirFghUjn5M7osioqilbLnv0FlnmCFrYXJLo8NITvh5TX6-qyPJhoDzM9k0Lh2xA7jBcUHRMOUUT0RiNZ7uQw4Ufm1OeA5qXTUWEF5stLYDpDWiZGGMq8BWLmmHg0kgKXQBykEc2S1UcpY4e4pDEDVblmZPi8zJvRZh1Pb6VxRcUgTZbiUXzHQHCG0Y3X_i9mwER0N1KdS-xS678Mf1wVIiOA_Q8VEnmmRrQHZiThg";

        $client = static::createClient();
        $data = json_encode([
            'receiver_id' => $receiverId,
            'content' => $content,
        ]);

        $crawler = $client->request('POST', '/api/send/message', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $token,
        ], $data);

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode(), 'Expected status code 201');
        $content = $response->getContent();
        $this->assertJson($content, 'Response is not valid JSON');
        $data = json_decode($content, true);
        $this->assertIsArray($data);
    }
}