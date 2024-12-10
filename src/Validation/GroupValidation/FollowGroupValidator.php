<?php


namespace App\Validation\GroupValidation;


use App\Entity\GroupTable;
use App\Entity\Subscribers;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FollowGroupValidator
{

    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate(UserInterface $user, $id){
        $group = $this->entityManager->getRepository(GroupTable::class)->findOneBy(['id_group' => $id]);

        if (!$group) {
            throw new \Exception("Группа не нашлос");
        }

        $users = $this->entityManager->getRepository(Users::class)->findOneBy(['id' => $user->getId()]);
        if(!$user){
            throw new \Exception("пользовател не найден");
        }

        $isSubscriber = $this->entityManager->getRepository(Subscribers::class)->findOneBy([
            'id_group' => $id,
            'id_users' => $users->getId(),
        ]);

        if ($isSubscriber) {
            throw new \Exception("Пользователь уже подписан в эту группу");
        }



        return [
            'group' => $group,
            'users' => $users,
        ];
    }

}