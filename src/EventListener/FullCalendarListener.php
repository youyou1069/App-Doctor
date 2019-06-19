<?php

namespace App\EventListener;

use App\Entity\Appointment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Toiba\FullCalendarBundle\Entity\Event;
use Toiba\FullCalendarBundle\Event\CalendarEvent;

class FullCalendarListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param CalendarEvent $calendar
     */
    public function loadEvents(CalendarEvent $calendar)
    {
        $startDate = $calendar->getStart();
        $endDate = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change b.beginAt by your start date in you custom entity
        $appointments = $this->em->getRepository(Appointment::class)
            ->createQueryBuilder('b')
            ->andWhere('b.startAt BETWEEN :startDate and :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
            ->getQuery()->getResult();

        foreach($appointments as $appointment) {

            // create the events with your own entity (here booking entity)
            $appointmentEvent = new Event(
//	            $booking->getPatient() ,
                $appointment->getTitle(),
                $appointment->getStartAt(),
                $appointment->getEndAt()

            // If end date is null or not defined, it create an all day event
            );

            /*
             * For more information see : Toiba\FullCalendarBundle\Entity\Event
             * and : https://fullcalendar.io/docs/event-object
             */
            // $bookingEvent->setBackgroundColor($booking->getColor());
            // $bookingEvent->setCustomField('borderColor', $booking->getColor());

            $appointmentEvent->setUrl(
                $this->router->generate('appointment_show', array(
                    'id' => $appointment->getId(),
                ))
            );

            // finally, add the booking to the CalendarEvent for displaying on the calendar
            $calendar->addEvent($appointmentEvent);
        }
    }
}