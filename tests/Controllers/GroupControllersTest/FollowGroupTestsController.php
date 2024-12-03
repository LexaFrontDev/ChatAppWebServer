<?php


namespace App\Tests\Controllers\GroupControllersTest;

use App\Entity\GroupTable;
use App\Entity\Users;
use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class FollowGroupTestsController extends WebTestCase
{

    public function testResponseFollowGroup()
    {
        $client  = $this->createAuthenticatedApiClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $repository = $entityManager->getRepository(GroupTable::class);
        $group = $repository->findOneBy(['nameGroup' => 'test1group']);
        $groupId = $group->getIdGroup();

        $client->request('POST', '/api/follow/' . $groupId, [], [], [], []);

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        var_dump($data);
    }

}