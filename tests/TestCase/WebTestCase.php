<?php


namespace App\Tests\TestCase;


use App\Entity\Users;
use App\DataFixtures\CreateUsersFixtures;
use App\Repository\UsersRepository;
use App\Service\TokenService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class WebTestCase extends BaseWebTestCase
{

    protected function createAnonymousApiClient(): KernelBrowser
    {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
        ]);
    }

    public function createAuthenticatedApiClient(string $user = "test1@gmail.com"): KernelBrowser
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $fixtures =  new CreateUsersFixtures($passwordHasher);
        $fixtures->load($entityManager);

        $user = static::getContainer()->get(UsersRepository::class)->findOneByEmail($user);

        if (!$user instanceof Users) {
            throw new \InvalidArgumentException('User not found.');
        }

        $token = static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);
        static::ensureKernelShutdown();

        return static::createClient([], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'HTTP_authorization' => 'Bearer ' . $token,
        ]);
    }

}