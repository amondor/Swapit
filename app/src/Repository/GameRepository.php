<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @return Game[] Returns an array of Game objects
     */
    public function findGameByName($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('LOWER(g.name) LIKE LOWER(:name)')
            ->setParameter('name', $value)
            ->getQuery()
            ->getResult();
    }

    public function findGamePopular()
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.aggregated_rating >= 70')
            ->andWhere('g.aggregated_rating_count >= 20')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
