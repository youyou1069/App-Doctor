<?php

namespace App\Repository;

use App\Entity\Appointment;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Appointment::class);
    }
	public function findActive(DateTime $date, $user)
	{
		return $this->createQueryBuilder('j')
		            ->andWhere('j.startAt > :date')
		            ->setParameter('date', $date)
					->andWhere('j.doctor =  :doctor')
					->setParameter('doctor', $user)
		            ->orderBy('j.startAt', 'ASC')
		            ->getQuery()
		            ->getResult();
	}
	public function findDate(DateTime $date)
	{
		return $this->createQueryBuilder('j')
		            ->andWhere('j.startAt > :date')
		            ->setParameter('date', $date)
		            ->orderBy('j.startAt', 'ASC')
		            ->getQuery()
		            ->getResult();
	}
	public function newEvent($title, $startAt, $endAt)
	{
		return $this
			->createQueryBuilder('e')
			->update('AppBundle:CalendarEvent', 'e')
			->set('e.startAt', '?1')
			->set('e.endAt', '?2')
			->where('e.title = ?3')
			->setParameter(1, $startAt)
			->setParameter(2, $endAt)
			->setParameter(2, $title)
			->getQuery()
			->getResult();
	}
    // /**
    //  * @return Appointment[] Returns an array of Appointment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Appointment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
