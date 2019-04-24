<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 *
 */
class BookingController extends AbstractController {
	private $repo;
	/**
	 * @var ObjectManager
	 */
	private $em;

	/**
	 * PropertyController constructor.
	 *
	 * @param BookingRepository $repo
	 * @param ObjectManager $sm
	 */
	public function __construct( BookingRepository $repo, ObjectManager $sm ) {
		$this->repo = $repo;
		/** @noinspection UnusedConstructorDependenciesInspection */
		/** @noinspection UnusedConstructorDependenciesInspection */
		$this->em = $sm;
	}


	/**
	 * @Route("booking/calendar", name="booking_calendar", methods={"GET"})
	 */
	public function calendarAction(): Response {
		return $this->render( 'booking/calendar.html.twig' );
	}

	/**
	 * @Route("/booking/", name="booking_index")
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction( PaginatorInterface $paginator, Request $request ) {

		$bookings = $paginator->paginate(
			$this->repo->findActive(new DateTime('-20 day')),
			$request->query->getInt( 'page', 1 ),
			5
		);

		return $this->render( 'booking/index.html.twig', [
			'current_menu' => 'bookings',
			'bookings'     => $bookings,
		] );
	}

	/**
	 * @Route("booking/new", name="booking_new", methods={"GET","POST"})
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function newAction( Request $request )
	{
		$booking = new Booking();
		$form    = $this->createForm( BookingType::class, $booking );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist( $booking );
			$entityManager->flush();

			return $this->redirectToRoute( 'booking_index' );
		}

		return $this->render( 'booking/new.html.twig', [
			'booking' => $booking,
			'form'    => $form->createView(),
		] );
	}


	/**
	 * @Route("booking/{id}", name="booking_show", methods={"GET"})
	 * @param Booking $booking
	 *
	 * @return Response
	 */
	public function showAction( Booking $booking ): Response {
		return $this->render( 'booking/show.html.twig', [
			'booking' => $booking,
		] );
	}

	/**
	 * @Route("booking/{id}/edit", name="booking_edit", methods={"GET","POST"})
	 * @param Request $request
	 * @param Booking $booking
	 *
	 * @return Response
	 */
	public function editAction( Request $request, Booking $booking ): Response {
		$form = $this->createForm( BookingType::class, $booking );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute( 'booking_index', [
				'id' => $booking->getId(),
			] );
		}

		return $this->render( 'booking/edit.html.twig', [
			'booking' => $booking,
			'form'    => $form->createView(),
		] );
	}


	/**
	 * @Route("booking/{id}", name="booking_delete", methods={"DELETE"})
	 */
	public function deleteAction( Booking $booking, ObjectManager $manager ) {
		$manager->remove( $booking );
		$manager->flush();

		return $this->redirectToRoute( 'booking_index' );
	}


}



///**
// * @Route("booking/calendar/new", name="calendar_new", methods={"GET","POST"})
// * @param Request $request
// *
// * @return Response
// */
//public function newCalendarAction( Request $request, ObjectManager $manager )
//{
//
//	dump($request);
//
//
//
//	$entityManager = $this->getDoctrine()->getManager();
//	$booking = new Booking();
////		$title= $request->query->get('title');
////		$beginAt= $request->query->get('start');
////		$endAt= $request->query->get('end');
////		$booking ->setPatient($manager->getReference('Booking', ($request->query->get('patient'))));
//
//	$booking ->setDoctor($request->query->get('doctor'));
//
//	$booking ->setTitle($request->query->get('title'));
//	$booking ->setBeginAt(new \DateTime($request->query->get('start')));
//	$booking ->setEndAt(new \DateTime($request->query->get('end')));
//
//	// tell Doctrine you want to (eventually) save the Product (no queries yet)
//	$entityManager->persist($booking);
//
//	// actually executes the queries (i.e. the INSERT query)
//	$entityManager->flush();
//
//	return new Response('Saved new product with id '.$booking->getId());
//}
