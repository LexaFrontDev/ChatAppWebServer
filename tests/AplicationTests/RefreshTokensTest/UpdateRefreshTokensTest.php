<?php

namespace App\Tests\AplicationTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateRefreshTokensTest extends WebTestCase
{
    public function testRefTokenUpdater()
    {
        $refreshToken = 'e17ab3955a89b290d110921827ad57fa1587892c9ad1d6273c74ddbc1b64aea4';
        $client = static::createClient();
        $data = ['refresh_token' => $refreshToken];
        $jsonData = json_encode($data);

        $client->request('POST', '/api/token/refresh', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json'
        ], $jsonData);

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $headers = $response->headers->all();
        $this->assertArrayHasKey('authorization', $headers, 'Заголовок "Authorization" отсутствует в ответе.');
        $accessToken = $headers['authorization'][0];
        echo "Access Token: $accessToken\n";
        $this->assertStringStartsWith('Bearer ', $accessToken, 'Токен не имеет префикса "Bearer "');
        return $accessToken;
    }
}

