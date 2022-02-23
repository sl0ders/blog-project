<?php

namespace App\Repository;

use App\Entity\PostNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostNote[]    findAll()
 * @method PostNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostNote::class);
    }

    // /**
    //  * @return PostNote[] Returns an array of PostNote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostNote
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
