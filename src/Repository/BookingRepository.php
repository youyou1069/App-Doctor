<?php

namespace App\Repository;

use App\Entity\Booking;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Booking::class);
    }

	public function findActive(DateTime $date)
	{
		return $this->createQueryBuilder('j')
		            ->andWhere('j.beginAt > :date')
		            ->setParameter('date', $date)
					->orderBy('j.beginAt', 'ASC')
		            ->getQuery()
		            ->getResult();
	}

	public function newEvent($title, $beginAt, $endAt)
	{
		return $this
			->createQueryBuilder('e')
			->update('AppBundle:CalendarEvent', 'e')
			->set('e.beginAt', '?1')
			->set('e.endAt', '?2')
			->where('e.title = ?3')
			->setParameter(1, $beginAt)
			->setParameter(2, $endAt)
			->setParameter(2, $title)
			->getQuery()
			->getResult();
	}
    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
