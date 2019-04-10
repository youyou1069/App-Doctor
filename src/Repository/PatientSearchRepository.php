<?php

namespace App\Repository;

use App\Entity\PatientSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PatientSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientSearch[]    findAll()
 * @method PatientSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientSearchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PatientSearch::class);
    }

    // /**
    //  * @return PatientSearch[] Returns an array of PatientSearch objects
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
    public function findOneBySomeField($value): ?PatientSearch
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
