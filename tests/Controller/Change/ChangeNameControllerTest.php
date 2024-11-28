<?php


namespace App\Tests\Controller\change;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class ChangeNameControllerTest extends WebTestCase
{

    public function testResponseChangeName()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/change/name', [
            'Body' => [
                'newName' => 'test11',
            ],
        ]);

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        var_dump($data);
    }

}