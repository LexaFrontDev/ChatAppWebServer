<?php

namespace App\Tests\AplicationTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateRefreshTokensTest extends WebTestCase
{
    public function testRefTokenUpdater()
    {
        $refreshToken = '8ed8a617837ac1286c569914417f51837e5383eb8876d0069f8a931bb62e6bc9';
        $client = static::createClient();
        $data = ['refresh_token' => $refreshToken];
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

