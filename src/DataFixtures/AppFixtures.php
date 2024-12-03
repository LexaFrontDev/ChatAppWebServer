<?php


namespace App\DataFixtures;


namespace App\DataFixtures;

use App\Entity\GroupTable;
use App\Entity\Messages;
use App\Entity\Subscribers;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\MessagesService\EncryptionService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encrypt;
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher, EncryptionService $encrypt)
    {
        $this->hasher = $hasher;
        $this->encrypt = $encrypt;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = $this->createUser($manager, 'test1', 'test1@gmail.com', 'test1234');
        $user2 = $this->createUser($manager, 'test2', 'test2@gmail.com', 'test1234');
        $this->createMessage($manager, $user1, $user2, 'с новым годом!');
        $this->createGroup($manager, 'test2');
        $manager->flush();
    }

    private function createUser(ObjectManager $manager, string $name, string $email, string $password): Users
    {
        $user = new Users();
        $user->setName($name);
        $user->setEmail($email);
        $hashedPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        return $user;
    }

    private function createMessage(ObjectManager $manager, Users $sender, Users $receiver, string $content): void
    {
        $message = new Messages();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $encryptedMessages = $this->encrypt->encryptMessage($content);
        $message->setContent($encryptedMessages['encrypted_message']);
        $message->setIv($encryptedMessages['iv']);
        $manager->persist($message);
    }


    private function createGroup(ObjectManager $manager, $name)
    {

        $createGroup = new GroupTable();
        $createGroup->setNameGroup('test1group');
        $createGroup->setDescription('testDescriptionGroup');
        $manager->persist($createGroup);
        $manager->flush();
        return $createGroup;
    }
}
