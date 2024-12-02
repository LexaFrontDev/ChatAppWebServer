<?php


namespace App\Tests\TestCase;


use App\Entity\Users;
use App\DataFixtures\AppFixtures;
use App\Repository\UsersRepository;
use App\Service\TokenService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EncryptMessages\EncryptionService;
use App\Service\RefreshTokenService;


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
        $createUsers = $this->createUsersForTest();

        $user = static::getContainer()->get(UsersRepository::class)->findOneByEmail($user);

        if (!$user instanceof Users) {
            throw new \InvalidArgumentException('User not found.');
        }

        $accToken = static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);
        $refToken = static::getContainer()->get(RefreshTokenService::class)->generateToken($user);
        static::ensureKernelShutdown();

        return static::createClient([], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'HTTP_authorization' => 'Bearer ' . $accToken,
        ]);
    }

    public function createUsersForTest()
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $encrypt = static ::getContainer()->get(EncryptionService::class);
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $fixtures =  new AppFixtures($passwordHasher, $encrypt);
        $fixtures->load($entityManager);
        $user = static::getContainer()->get(UsersRepository::class)->findOneByEmail("test1@gmail.com");
        $refToken = static::getContainer()->get(RefreshTokenService::class)->generateToken($user);
    }

}