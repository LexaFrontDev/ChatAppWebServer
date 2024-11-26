<?php

namespace App\Tests\AplicationTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\AplicationTests\AuthTest\RegistrationTest;

class UpdateRefreshTokensTest extends WebTestCase
{
    public function testRefTokenUpdater()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'testuser',
            'email' => 'testuser@gmail.com',
            'password' => 'test1234'
        ]));

        $response = $client->getResponse();
        $headers = $response->headers->all();
        $refToken = $headers['x-ref-token'][0];

        $data = ['refresh_token' => $refToken];
        $jsonData = json_encode($data);

        $client->request('POST', '/api/token/refresh', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json'
        ], $jsonData);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $headers = $response->headers->all();
        $this->assertArrayHasKey('x-acc-token', $headers, 'Заголовок "X-Acc-Token" отсутствует в ответе.');
        $accessToken = $headers['x-acc-token'][0];
        echo "Access Token: $accessToken\n";
        return $accessToken;
    }
}

