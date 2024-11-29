<?php


namespace App\Tests\Controllers\ResetPasswordControllerTest;

use App\Entity\MailVeryfication;
use App\Entity\Users;
use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class ResetPasswordControllerTest extends WebTestCase
{

    public function testBadResponseWrongEmailTest()
    {
        $client = $this->createClient();
        $createUsers = $this->createUsersForTest();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $client->request('POST', '/api/reset/password/send', [], [], ['Content-Type' => 'application/json'], json_encode(['email' => 'test111@gmail.com']));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }

    public function testBadResponseWrongCode()
    {
        $client = $this->createClient();
        $createUsers = $this->createUsersForTest();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $client->request('POST', '/api/reset/password/send', [], [], ['Content-Type' => 'application/json'], json_encode(['email' => 'test1@gmail.com']));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);

        $client->request('POST', '/api/reset/password/reset', [], [], [ 'Content-Type' => 'application/json'], json_encode([
            'email' => 'test1@gmail.com',
            'code' => 465456,
            'newPassword' => 'test123456'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }

    public function testResponseResetPassword()
    {
        $client = $this->createClient();
        $createUsers = $this->createUsersForTest();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $client->request('POST', '/api/reset/password/send', [], [], ['Content-Type' => 'application/json'], json_encode(['email' => 'test1@gmail.com']));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);

        $repositoryVerify = $entityManager->getRepository(MailVeryfication::class);
        $codeTable = $repositoryVerify->findOneBy(['email' => 'test1@gmail.com']);
        $code = $codeTable->getCode();

        $client->request('POST', '/api/reset/password/reset', [], [], [ 'Content-Type' => 'application/json'], json_encode([
                'email' => 'test1@gmail.com',
                'code' => $code,
                'newPassword' => 'test123456'
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }



}