<?php


namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }



    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findOneByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function isUserUnique($name, $email)
    {
        $count = $this->createQueryBuilder('u')
            ->where('u.name = :name')
            ->andWhere('u.email = :email')
            ->setParameter('name', $name)
            ->setParameter('email', $email)
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $count == 0;
    }

    public function isVerified($email)
    {
        $user = $this->findOneBy(['email' => $email]);
        return $user !== null && $user->isVerified();
    }

    public function save(Users $user)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
