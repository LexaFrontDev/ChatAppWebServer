<?php


namespace App\Tests\Controllers\RefreshTokenControllers;

use App\Tests\TestCase\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\RefreshToken;


class RefreshTokenControllerTest extends WebTestCase
{

    public function testBadResponseRefresh()
    {
        $client = $this->createClient();
        $createUsers = $this->createUsersForTest();
        $client->request('POST', '/api/token/refresh', [], [], ['Content-Type' => 'application/json'], json_encode([
            'refresh_token' => 'NOT'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST , $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }


    public function testResponseRefreshController()
    {
        $client = $this->createClient();
        $createUsers = $this->createUsersForTest();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $repository = $entityManager->getRepository(RefreshToken::class);
        $isRefresh  = $repository->findOneBy(['username' => 'test1@gmail.com']);
        $refToken = $isRefresh->getRefreshToken();

        $client->request('POST', '/api/token/refresh', [], [], [], json_encode([
           'refresh_token' => $refToken
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }

}