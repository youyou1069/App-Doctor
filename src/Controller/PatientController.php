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
//		Récupération de l'identifiant de l'utilisateur connecté
		$user = $this->getUser()->getId();
//		Creation d'une pagination avec KnpPaginator
		$patients = $paginator->paginate(
//	    Requête qui permet de sélectionner tous les éléments de la table patient
//		en passant un paramètre l'idendifiant de l'utlisateur
			$this->repo->findBy( [ 'DOCTOR' => $user ] ),
			$request->query->getInt( 'page', 1 ),
			8
		);
//		Affiche la vue, en passant un tableau contenant tous les enregistrements de la table.
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
	public function newAction( Request $request, ObjectManager $manager )
	{
		// 1) Construction du formulaire
//		Récupérer l'utilisateur connecté
		$user = $this->getUser();
//	    Créer un nouvel objet de type Patient
		$patient = new Patient();
//		Affecter une valeur user l'attribut Doctor
		$patient->setDOCTOR($user);
//		création d’un objet de type form. Cet objet va contenir le formulaire qui sera affiché.
//      La création d’un formulaire nécessite au moins deux paramètres : un formType, et un objet correspondant au type pour lequel le formulaire est destiné
		$form    = $this->createForm( PatientType::class, $patient );
		// 2) Gérer la soumission du formulaire
//		Coïncider les éléments  issus du navigateur avec les données attendues dans le formulaire précédemment initialisé,
//      elle recopie les données du navigateur dans les propriétés de l’objet.
		$form->handleRequest( $request );
//		Ce test permet de vérifier si le formulaire a ´été bien soumis (POST)
//      et si les données saisies sont valides avec le descriptif donné dans le formType
		if ( $form->isSubmitted() && $form->isValid() ) {
			// 4) Enregistrer le patient
//			EntityManager demande à doctrine de persister  l’objet dans la file d’attente de la base de données.
//          A ce stade $patient contient les informations saisies par l’utilisateur.
			$manager->persist( $patient );
//			EntityManager demande à doctrine  Doctrine d'exécuter effectivement les requêtes nécessaires
//          pour sauvegarder les entités qu'on lui a demandé de persister précédemment
			$manager->flush();
			$this->addFlash( 'success', 'Le patient ajouté avec succès' );
//			L’application est redirigée vers la page de la liste des patients
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
	 * @Route ("/patient/{id}", name="patient_show")
	 * @param Patient $patient
	 * @return Response
	 */
	public function showAction( Patient $patient ): Response {
		return $this->render( 'admin/patient/show.html.twig', [ 'patient' => $patient ] );
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

