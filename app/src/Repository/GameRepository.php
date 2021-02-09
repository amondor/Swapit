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
    public function search(array $filters)
    {
        $queryBuilder = $this->createQueryBuilder('g');
        foreach ($filters as $key => $filter)
        {
            if (is_array($filter)) {
                $queryBuilder->join('g'.$key, $key);
                foreach ($filter as $param)
                {
                    $varname = substr($param,0,3);
                    $queryBuilder
                        ->orWhere("$key.name = :$varname")
                        ->setParameter($varname, $param);
                }
            } 
            else { 
                // $separator = ($key == 'name')? ' LIKE ':' = ';
                    // $queryBuilder 
                    //     ->andWhere("$key.$key $separator $key")
                    //     ->setParameter($key, $filter);
            }
        }
        // $separator = ($key == 'name')? ' LIKE ':' = ';
        // dd($key);
        $queryBuilder
            ->andWhere("$key".".$key LIKE :$key")
            ->setParameter($key, $filter)       
            ->orderBy('g.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function save(Game $game)
    {
        $em = $this->getRepository(Game::class);
        if(!$em->find($game->getId())) {
            $em->persist($game);
            $em->flush();
        }
    }
    
    
    // /**
    //  * @return Game[] Returns an array of Game objects
    //  */
    /*
    public function findByExampleField($value)
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
