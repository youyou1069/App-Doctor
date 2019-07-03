<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Patient;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Knp\Component\Pager\PaginatorInterface;




class AppointmentController extends AbstractController
{
	private $repo;
	/**
	 * @var ObjectManager
	 */
	private $em;

	/**
	 * PropertyController constructor.
	 *
	 * @param AppointmentRepository $repo
	 * @param ObjectManager $sm
	 */
	public function __construct( AppointmentRepository $repo, ObjectManager $sm ) {
		$this->repo = $repo;
		/** @noinspection UnusedConstructorDependenciesInspection */
		/** @noinspection UnusedConstructorDependenciesInspection */
		$this->em = $sm;
	}


	/**
	 * @Route("appointment/calendar", name="appointment_calendar", methods={"GET"})
	 */
	public function calendarAction(): Response {
		return $this->render( 'appointment/calendar.html.twig' );
	}

	/**
	 * @Route("/appointment_user", name="appointment_user_index")
	 * @return Response
	 * @throws Exception
	 */
	public function indexUserAction(): Response
	{
		$repo= $this->getDoctrine()->getRepository(Appointment::class);
//		Récupération de l'identifiant de l'utilisateur connecté
		$user = $this->getUser()->getId();
//		Creation d'une pagination avec KnpPaginator
		$appointments = $repo->findActive(new DateTime('-12hours'), $user );
//		Affiche la vue, en passant un tableau contenant tous les enregistrements de la table.
		return $this->render( 'appointment/index.html.twig', [
			'current_menu' => '$appointments',
			'appointments'     => $appointments,
		] );
	}

	/**
	 * @Route("/appointment/", name="appointment_index")
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 * @throws Exception
	 */
	public function indexAction( PaginatorInterface $paginator, Request $request ): Response
	{
		$appointments = $paginator->paginate(
			$this->repo->findDate(new DateTime('-12 hours')),
			$request->query->getInt( 'page', 1 ),
			20
		);
		return $this->render( 'appointment/index.html.twig', [
			'current_menu' => 'appointments',
			'appointments'     => $appointments,
		] );
	}

	/**
	 * @Route("appointment/new", name="appointment_new", methods={"GET","POST"})
	 * @param Request $request
	 * @return Response
	 */
	public function newAction( Request $request ): Response
	{
		$appointment = new appointment();
//      si user a le role doctor
		if (true === $this->get('security.authorization_checker')->isGranted('ROLE_DOCTOR')){
//		Récupérer l'utilisateur connecté
			$user = $this->getUser();
//		Hydrater l'attribut Doctor
			$appointment->setDoctor($user);
		}
//		si Id du patient est passé en URL
		if (isset($_GET['id'])){
//	    récupérer l'id passé en  GET
			$id= $request->query->get('id');
//		récupérer le patient dans la bdd
			$em = $this->getDoctrine()->getManager();
			$patient = $em->find(Patient::class, $id);
//		    dump($patient);die;
//		    Hydrater l'attribut patient
			$appointment->setPatient($patient);
		}

		$form    = $this->createForm( AppointmentType::class, $appointment );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist( $appointment );
			$entityManager->flush();
			$this->addFlash('success', 'Le rendez-vous à bien enregistré');
			if (true === $this->get('security.authorization_checker')->isGranted('ROLE_DOCTOR')) {
				return $this->redirectToRoute( 'appointment_user_index' );
			}
			return $this->redirectToRoute( 'appointment_index' );
			}


		return $this->render( 'appointment/new.html.twig', [
			'appointment' => $appointment,
			'form'    => $form->createView(),
		] );
	}


	/**
	 * @Route("appointment/{id}", name="appointment_show", methods={"GET"})
	 * @param Appointment $appointment
	 *
	 * @return Response
	 */
	public function showAction( Appointment $appointment ): Response {
		return $this->render( 'appointment/show.html.twig', [
			'appointment' => $appointment,
		] );
	}

	/**
	 * @Route("appointment/{id}/edit", name="appointment_edit", methods={"GET","POST"})
	 * @Route("appointment/{id}/edit/{mode}/{pid}", name="appointment-edit", methods={"GET","POST"})
	 * @param null $pid
	 * @param null $mode
	 * @param Request $request
	 * @param Appointment $appointment
	 *
	 * @return Response
	 */
	public function editAction($pid=null, $mode=null,  Request $request, Appointment $appointment): Response {
		$form = $this->createForm( AppointmentType::class, $appointment );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$this->getDoctrine()->getManager()->flush();
			$this->addFlash('success', 'Le rendez-vous à bien modifié');
			if($mode===null){
				return $this->redirectToRoute( 'appointment_index', [
					'id' => $appointment->getId(),
				] );
			}
				return $this->redirectToRoute( 'patient_show', [
					'id' => $pid,
				] );
			}

		return $this->render( 'appointment/edit.html.twig', [
			'appointment' => $appointment,
			'form'    => $form->createView(),
		] );
	}


	/**
	 * @Route("appointment/{id}", name="appointment_delete", methods={"DELETE"})
	 * @param Appointment $appointment
	 * @param ObjectManager $manager
	 * @return RedirectResponse
	 */
	public function deleteAction( Appointment $appointment, ObjectManager $manager ): RedirectResponse
	{
		$manager->remove( $appointment );
		$manager->flush();
		$this->addFlash( 'success', 'Le rendez-vous a bien été supprime' );

		return $this->redirectToRoute( 'appointment_index' );
	}
	/**
	 * @Route("/item/deleteAll_appointment/", name="delete_item_appointment", methods={"POST"})
	 * @param ObjectManager $manager
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function deleteAll( ObjectManager $manager, Request $request ): JsonResponse {

		$ids = explode( ',', $request->get( 'ids' ) );
		foreach ( $ids as $id ) {
			$appointments = $this->repo->findBy( array( 'id' => $ids ) );
			foreach ( $appointments as $appointment ) {
				$manager->remove( $appointment );
			}
			$manager->flush();

			return new JsonResponse( $ids );
		}
		$this->addFlash( 'success', 'le RDV a bien été supprimé' );

		return new JsonResponse( 'Pas de résultats', Response::HTTP_NOT_FOUND );
	}
}
