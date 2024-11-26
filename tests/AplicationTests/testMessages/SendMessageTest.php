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
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzI2MTQ3NDMsImV4cCI6MTczMjYxODM0Mywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiTGV4YURldjEwQGdtYWlsLmNvbSJ9.wIEH1S3vpd24eX3HAOcRpSTGeTJ6igcBfiuk-bF3AVeTVEBuTFFwhrFhrTbTp1cG37sqPqGdda3N0QGKpcSTkm_JfIPZpzASeU2kv2ScWkTVdbi_XqQUS3hHcxzCXt_NvP45o88dbagjBvhleaegncCTGrhbghfumfQMQmOPwL7-euQsiFB3XHc9y20k2-_Q6h0gGhe0W89mrQF6G8uTnknvX405Tv6Hg-pwTQixkUk7SB8wBAo8X9uLN-k2ei27aER1GXk7rrfejj6oOchLdAFywuwbckBEiFHyl887mKeapU80jiGrWOXi2d5ChdGBNzF7gEhDBCKE2tmn1ZIb3w";

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
        $responseHeaders = $response->headers->all();
        echo "Response Headers:\n";
        var_dump($responseHeaders);
        $content = $client->getResponse()->getContent();
        $this->assertJson($content);
        $data = json_decode($content, true);
        $this->assertIsArray($data);


    }
}