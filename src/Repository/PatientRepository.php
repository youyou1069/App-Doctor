<?php

namespace App\Repository;

use App\Entity\Patient;
use App\Entity\PatientSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    /**
     * @return Query
     */
    public function findAllQuery(PatientSearch $search):Query
    {
        $query= $this->findQuery();

        if($search->getFirstName()){
            $query = $query
                ->andWhere('p.firstName = :firstName')
                ->setParameter('firstName', $search->getFirstName());
        }

        if($search->getLastName()){
            $query = $query
                ->andWhere('p.lastName = :lastName')
                ->setParameter('lastName', $search->getLastName());
        }
        if($search->getNir()){
            $query = $query
                ->andWhere('p.nir = :nir')
                ->setParameter('nir', $search->getNir());
        }

        if($search->getPostcode()){
            $query = $query
                ->andWhere('p.postcode = :postcode')
                ->setParameter('postcode', $search->getPostcode());
        }
        return $query->getQuery();
    }


    /**
     * @return QueryBuilder
     */
    private function findQuery():QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id');


    }

    // /**
    //  * @return Patient[] Returns an array of Patient objects
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
    public function findOneBySomeField($value): ?Patient
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
