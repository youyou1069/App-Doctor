<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\PatientSearch;
use App\Form\PatientSearchType;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class PatientController extends AbstractController {
	private $repo;
	/**
	 * @var ObjectManager
	 */
	private $em;

	/**
	 * PropertyController constructor.
	 *
	 * @param PatientRepository $repo
	 * @param ObjectManager $sm
	 */
	public function __construct( PatientRepository $repo, ObjectManager $sm ) {
		$this->repo = $repo;
		/** @noinspection UnusedConstructorDependenciesInspection */
		/** @noinspection UnusedConstructorDependenciesInspection */
		$this->em = $sm;
	}

	/**
	 * @Route("/patient/", name="patient_index")
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction( PaginatorInterface $paginator, Request $request ) {
		$search = new PatientSearch();
		$form   = $this->createForm( PatientSearchType::class, $search );
		$form->handleRequest( $request );
//        var_dump($search);
		$patients = $paginator->paginate(
			$this->repo->findAllQuery( $search ),
			$request->query->getInt( 'page', 1 ),
			5
		);

		return $this->render( 'admin/patient/index.html.twig', [
			'current_menu' => 'patients',
			'patients'     => $patients,
			'form'         => $form->createView()
		] );
	}

	/**
	 * @Route("/patient/new/", name="patient_new")
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @param UserPasswordEncoderInterface $encoder
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 * @throws \Exception
	 */
	public function newAction( Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder ) {
		// 1) build the form
		$patient = new Patient();
		$form    = $this->createForm( PatientType::class, $patient );
		// 2) handle the submit (will only happen on POST)
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			// 3) Encode the password (you could also do this via Doctrine listener)
//            $hash = $encoder->encodePassword($patient, $patient->getPassword());
//            $patient->setPassword($hash);

//          $patient->addRole("ROLE_ADMIN");
//            $patient->addRole("ROLE_patient");
			// 4) save the patient!
			$manager->persist( $patient );
			$manager->flush();
			$this->addFlash( 'sucess', 'patient ajouté avec succès' );

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
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
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
	 */
	public function deleteAction( Patient $patient, ObjectManager $manager)
	{

		$manager->remove( $patient );
		$manager->flush();

		return $this->redirectToRoute( 'patient_index' );

	}


	/**
	 * @Route ("/patient/{id}", name="patient_show")
	 * @param Patient $patient
	 * @return Response
	 */
	public function showAction( Patient $patient ) {
		return $this->render( 'admin/patient/show.html.twig', [ 'patient' => $patient ] );

	}

	/**
	 * @Route("/Patient/search", name="patient_search")
	 * @Method({"GET"})
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function searchAction( Request $request ) {
		$searchString = $request->get( 'q' );
		if ( strlen( $searchString ) < 2 ) {
			return new Response( "Invalid search query", 406 );
		}
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


	/**
	 * @Route("/item/deleteAll/", name="delete_item", methods={"POST"})
	 *
	 */
	public function deleteAll(ObjectManager $manager, Request $request)
	{

		$ids = explode(",", $request->get('ids'));
		foreach ($ids as $id){
			$patients = $this->repo->findBy(array('id' => $ids));
			foreach ($patients as $patient){
				$manager->remove( $patient );
			}
			$manager->flush();
			return new JsonResponse($ids);
		}

		return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
	}

}

