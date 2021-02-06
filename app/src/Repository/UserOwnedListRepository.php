<?php

namespace App\Repository;

use App\Entity\UserOwnedList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserOwnedList|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOwnedList|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOwnedList[]    findAll()
 * @method UserOwnedList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOwnedListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnedList::class);
    }

    // /**
    //  * @return UserOwnedList[] Returns an array of UserOwnedList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserOwnedList
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
