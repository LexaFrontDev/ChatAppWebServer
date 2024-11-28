<?php


namespace App\Tests\Controller\Change;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class ChangeNameControllerTest extends WebTestCase
{

    public function testResponseChangeName()
    {
        $client  = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/change/name',[], [], [], json_encode(['newName' => 'testChangeName',]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }

}