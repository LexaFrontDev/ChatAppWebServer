<?php

namespace App\Repository;

use App\Entity\MailVeryfication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MailVeryfication>
 */
class MailVeryficationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailVeryfication::class);
    }



    public function isMailUnique($email)
    {
        $isMail = $this->findOneBy(['email' => $email]);
        if (!$isMail) {
            return false;
        }
        return $isMail;
    }


    public function save($object)
    {
        $this->persist($object);
        $this->flush();
    }

    public function persist($entity)
    {
        MailVeryfication::persist($entity);
    }


}