<?php


namespace App\Singleton;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class EntityManagerSingleton
{
    private static ?EntityManagerSingleton $instance = null;
    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public static function getInstance(EntityManagerInterface $entityManager)
    {
        if (self::$instance === null) {
            self::$instance = new self($entityManager);
        }

        return self::$instance;
    }


    public function getRepository(string $class): EntityRepository
    {
        return $this->entityManager->getRepository($class);
    }


    public function save(object $entity)
    {
        if ($entity) {
            $this->entityManager->persist($entity);
        }

        $this->flush();
    }


    public function remove(object $entity)
    {
        $this->entityManager->remove($entity);
        $this->save();
    }


    public function persist(object $entity)
    {
        $this->entityManager->persist($entity);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }
}
