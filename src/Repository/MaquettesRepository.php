<?php

namespace App\Repository;

use App\Entity\Maquettes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Maquettes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maquettes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maquettes[]    findAll()
 * @method Maquettes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaquettesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maquettes::class);
    }

    // /**
    //  * @return Maquettes[] Returns an array of Maquettes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Maquettes
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
