<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\PatientSearch;
use App\Form\PatientSearchType;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PatientController extends AbstractController {
	private $repo;

	public function __construct( PatientRepository $repo ) {
		$this->repo = $repo;
	}

	/**
	 * @Route("/patient", name="patient_index")
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
	public function indexAction(PaginatorInterface $paginator, Request $request ): Response
	{
		$user = $this->getUser()->getId();
		$patients = $paginator->paginate(
			$this->repo->findBy( [ 'DOCTOR' => $user ] ),
			$request->query->getInt( 'page', 1 ),
			4
		);

		return $this->render( 'admin/patient/index.html.twig', [
			'current_menu' => 'patients',
			'patients'     => $patients,
		] );
	}

	/**
	 * @Route("/patient/rechercher/", name="patient_rechercher")
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function rechercherAction( PaginatorInterface $paginator, Request $request ): Response {
		$search = new PatientSearch();
		$form   = $this->createForm( PatientSearchType::class, $search );
//		$user=$this->getUser()->getId();
		$form->handleRequest( $request );
//        var_dump($search);
//		$this->addFlash('error', 'Pas de résultats');
		if ( $form->isSubmitted() && $form->isValid() ) {
			$patients = $paginator->paginate(
				$this->repo->findAllQuery( $search ),
				$request->query->getInt( 'page', 1 ),
				9
			);

			return $this->render( 'admin/patient/index.html.twig', [
				'current_menu' => 'patients',
				'patients'     => $patients,
				'form'         => $form->createView()
			] );
		}

		return $this->render( 'admin/patient/search.html.twig', [
			'form' => $form->createView()
		] );
	}

	/**
	 * @Route("/patient/new/", name="patient_new")
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @return RedirectResponse|Response
	 * @throws Exception
	 */
	public function newAction( Request $request, ObjectManager $manager ) {
		// 1) build the form
		$user = $this->getUser();
		$patient = new Patient();
		$patient->setDOCTOR($user);
		$form    = $this->createForm( PatientType::class, $patient );
		// 2) handle the submit (will only happen on POST)
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			// 4) save the patient!
			$manager->persist( $patient );
			$manager->flush();
			$this->addFlash( 'success', 'Le patient ajouté avec succès' );

			return $this->redirectToRoute( 'patient_index' );
		}
		return $this->render( 'admin/patient/new.html.twig', [
			'patient' => '$patient',
			'form'    => $form->createView()
		] );
	}

	/**
	 * @Route("/patient{id}/edit/", name="patient_edit", methods={"GET|POST"})
	 * @param Patient $patient
	 * @param Request $request
	 * @param ObjectManager $manager
	 *
	 * @return RedirectResponse|Response
	 */
	public function editAction( Patient $patient, Request $request, ObjectManager $manager ) {
		$form = $this->createForm( PatientType::class, $patient );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$manager->flush();
			//confirmation de la mise à jour
			$this->addFlash( 'success', 'le patient a Bien été modifié' );

			return $this->redirectToRoute( 'patient_index' );
		}

		return $this->render( 'admin/patient/edit.html.twig', [
			'form'    => $form->createView(),
			'patient' => $patient,

		] );

	}


	/**
	 * @Route("/patient/{id}/delete/", name="patient_delete", methods={"DELETE"})
	 * @param Patient $patient
	 * @param ObjectManager $manager
	 *
	 * @return RedirectResponse
	 */
	public function deleteAction( Patient $patient, ObjectManager $manager ): RedirectResponse {
		$manager->remove( $patient );
		$manager->flush();
		$this->addFlash( 'success', 'le patient a Bien été supprimé' );

		return $this->redirectToRoute( 'patient_index' );

	}

	/**
	 * @Route ("/patient/{id}", name="patient_show")
	 * @param Patient $patient
	 *
	 * @return Response
	 */
	public function showAction( Patient $patient ): Response {
		return $this->render( 'admin/patient/show.html.twig', [ 'patient' => $patient ] );
	}


	/**
	 * @Route("/item/deleteAll/", name="delete_item", methods={"POST"})
	 * @param ObjectManager $manager
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function deleteAll( ObjectManager $manager, Request $request ): JsonResponse {

		$ids = explode( ',', $request->get( 'ids' ) );
		foreach ( $ids as $id ) {
			$patients = $this->repo->findBy( array( 'id' => $ids ) );
			foreach ( $patients as $patient ) {
				$manager->remove( $patient );
			}
			$manager->flush();

			return new JsonResponse( $ids );
		}
		$this->addFlash( 'success', 'le patient a Bien été supprimé' );

		return new JsonResponse( 'Pas de résultats', Response::HTTP_NOT_FOUND );
	}

	/**
	 * TetranzSelect2EntityBundle
	 * @Route("/Patient/search", name="patient_search")
	 * @Method({"GET"})
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function searchAction( Request $request ): Response {
		$searchString = $request->get( 'q' );
		if ( strlen( $searchString ) < 2 ) {
			return new Response( 'Invalid search query', 406 );
		}
//		$user= $this->getUser()->getId();
//		dump($searchString);
		$entityManager = $this->getDoctrine()->getManager();
		$query         = $entityManager->createQuery( '
            SELECT a FROM App:Patient a
            WHERE a.lastName LIKE :searchString
            OR a.firstName LIKE :searchString
            ORDER BY a.firstName ASC           
       ' )
		                               ->setParameter( 'searchString', '%' . $searchString . '%' );

		$patients = $query->getResult();
//		dump( $patients );
		$patientsArray = array();
		foreach ( $patients as $patient ) {
			$patientsArray[] = array(
				'id'   => $patient->getId(),
				'text' => $patient->__toString()
			);
		}

		return new Response( json_encode( $patientsArray ), 200, array( 'Content-Type' => 'application/json' ) );
	}

}

