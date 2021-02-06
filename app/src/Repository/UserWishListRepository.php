<?php

namespace App\Repository;

use App\Entity\UserWishList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserWishList|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWishList|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWishList[]    findAll()
 * @method UserWishList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWishListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWishList::class);
    }

    // /**
    //  * @return UserWishList[] Returns an array of UserWishList objects
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
    public function findOneBySomeField($value): ?UserWishList
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
