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
use App\Repository\ConsultationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function newAction(Request $request, ObjectManager $manager)
    {

        $entity = new Consultation();
        $form = $this->createForm(ConsultationType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($entity);
            $manager->flush();
            $this->addFlash('sucess', 'La consultation a été enregistré avec succès');

        return $this->redirect($this->generateUrl('consultation_show', array('id' => $entity->getId())));
        }

        return $this->render('admin/consultation/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
	/**
	 * @Route ("consultation/{id}", name="consultation_show")
	 */
	public function showAction(Consultation $consultation)
	{
		$nom = $consultation->getPatient();
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
		$dompdf->stream("$nom.pdf", [
			"Attachment" => false
		]);

	}

//	/**
//	 * @Route ("consultation/{id}", name="consultation_show")
//	 */
//	public function showAction (Consultation $consultation)
//	{
//
//		return $this->render('admin/consultation/show.html.twig', [ 'consultation'=> $consultation   ] );
//
//	}

//    /**
//     *  @Route ("consultation/{id}", name="consultation_show")
//     */
//    public function showAction (Consultation $consultation)
//    {
////        return $this->render('admin/consultation/show.html.twig', [ 'consultation'=> $consultation   ] );
//	    return  $this->get('knp_snappy.pdf')->generateFromHtml(
//		    $this->renderView(
//			    'admin/consultation/show.html.twig',
//			    array(
//				    'consultation'  => $consultation
//			    )
//		    ),
//		    '/path/to/the/file.pdf'
//	    );
//    }
}
