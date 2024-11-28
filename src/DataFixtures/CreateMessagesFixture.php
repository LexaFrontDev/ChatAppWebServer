<?php


namespace App\DataFixtures;

use App\Entity\Messages;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\EncryptMessages\EncryptionService;

class CreateMessagesFixture extends Fixture
{
    private $encrypt;

    public function __construct(EncryptionService $encrypt)
    {
        $this->encrypt = $encrypt;
    }


    #[Group('messages')]
    public function load(ObjectManager $manager): void
    {
        $sender = $manager->getRepository(Users::class)->findOneBy(['name' => 'test1']);
        $receiver = $manager->getRepository(Users::class)->findOneBy(['name' => 'test2']);
        $message = new Messages();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $encryptedMessages = $this->encrypt->encryptMessage('с новым годом!');
        $message->setContent($encryptedMessages['encrypted_message']);
        $message->setIv($encryptedMessages['iv']);
        $manager->persist($message);
        $manager->flush();
    }
}
