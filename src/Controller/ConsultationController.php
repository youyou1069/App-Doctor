<?php
/**
 * Created by PhpStorm.
 * User: REMYELFI
 * Date: 15/03/2019
 * Time: 12:13
 */

namespace App\Controller;


use App\Entity\Consultation;
use App\Entity\MedicalHistory;
use App\Entity\Patient;
use App\Form\ConsultationType;
use App\Form\PatientType;
use App\Repository\ConsultationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ConsultationController extends AbstractController
{
    private $repo;
    /**
     * @var ObjectManager
     */
    private $em;

	/**
	 * PropertyController constructor.
	 *
	 * @param ConsultationRepository $repo
	 * @param ObjectManager $sm
	 */
    public function __construct(ConsultationRepository $repo, ObjectManager $sm)
    {
        $this->repo = $repo;
        /** @noinspection UnusedConstructorDependenciesInspection */
        /** @noinspection UnusedConstructorDependenciesInspection */
        $this->em = $sm;
    }

    /**
     * @Route("/consultation/", name="consultation_index")
     */
    public function indexAction()
    {
        return $this->render('admin/consultation/index.html.twig');
    }

	/**
	 * @Route("/consultation/new", name="consultation_new")
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @return RedirectResponse|Response
	 * @throws \Exception
	 */
    public function newAction( Request $request, ObjectManager $manager)
    {
	    $entity = new Consultation();
//	    dump($request->getMethod());die;
//	    si id Patient est passé dans l'url
	    if (isset($_GET['id'])){
//	    récupérer l'id passé en url
			$id= $request->query->get('id');
			$em = $this->getDoctrine()->getManager();
			$patient = $em->find(Patient::class, $id);
//		    dump($patient);die;
//		    Hydrater l'attribut patient
			$entity->setPatient($patient);
		}
        $form = $this->createForm(ConsultationType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($entity);
            $manager->flush();
            $this->addFlash('success', 'La consultation a été enregistré avec succès');
	        $idPatient=$entity->getPatient()->getId();

        return $this->redirect($this->generateUrl('patient_show', array('id' => $idPatient)));
        }
        return $this->render('admin/consultation/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

	/**
	 * @Route ("consultation/{id}", name="consultation_show")
	 * @param Consultation $consultation
	 */
	public function showAction(Consultation $consultation): void
	{
		$name = $consultation->getPatient();
		// Configure Dompdf according to your needs
		$pdfOptions = new Options();
		$pdfOptions->set('defaultFont', 'Arial');
		// Instantiate Dompdf with our options
		$dompdf = new Dompdf($pdfOptions);
		// Retrieve the HTML generated in our twig file
		$html = $this->renderView('admin/consultation/show.html.twig', [
			'consultation'=> $consultation,
		]);
		// Load HTML to Dompdf
		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
		$dompdf->setPaper('A4', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser (force download)
		$dompdf->stream("$name.pdf", [
			'Attachment' => false
		]);
	}
	/**
	 * @Route("/consultation{id}/edit/", name="consultation_edit", methods={"GET|POST"})
	 * @param Consultation $consultation
	 * @param Request $request
	 * @param ObjectManager $manager
	 *
	 * @return RedirectResponse|Response
	 */
	public function editAction (Consultation $consultation, Request $request, ObjectManager $manager )
	{

		$form = $this->createForm( ConsultationType::class, $consultation );

		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$manager->flush();
			//confirmation de la mise à jour
			$this->addFlash( 'success', 'l\'ordonnance a Bien été modifiée' );
			$idPatient=$consultation->getPatient()->getId();

			return $this->redirect($this->generateUrl('patient_show', array ( 'id' => $idPatient)));
		}

		return $this->render( 'admin/consultation/edit.html.twig', [
			'form'    => $form->createView(),
			'consultation' => $consultation,

		] );

	}
	/**
	 * @Route("/consultation/{id}/delete/", name="consultation_delete", methods={"DELETE"})
	 * @param Consultation $consultation
	 * @param ObjectManager $manager
	 */
	public function deleteAction ( Consultation $consultation, ObjectManager $manager)
	{

		$manager->remove( $consultation );
		$manager->flush();
		$this->addFlash( 'success', 'l\'ordonnance a Bien été supprimée' );
		$idPatient=$consultation->getPatient()->getId();

		return $this->redirect($this->generateUrl('patient_show', array ( 'id' => $idPatient)));

	}



}
