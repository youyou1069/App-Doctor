<?php

namespace App\Controller;


use App\Entity\MedicalHistory;
use App\Form\MedicalHistoryType;
use App\Repository\MedicalHistoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MedicalHistoryController extends AbstractController
{
    private $repo;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * PropertyController constructor.
     * @param MedicalHistoryRepository $repo
     * @param ObjectManager $sm
     */
    public function __construct(MedicalHistoryRepository $repo, ObjectManager $sm)
    {
        $this->repo = $repo;
        /** @noinspection UnusedConstructorDependenciesInspection */
        /** @noinspection UnusedConstructorDependenciesInspection */
        $this->em = $sm;
    }

    /**
     * @Route("/medHistory/", name="medHistory_index")
     */
    public function index()
    {
        return $this->render('admin/MedHistory/index.html.twig');
    }

    /**
     * @Route("/medHistory/new", name="medHistory_new")
     */
    public function new(Request $request, ObjectManager $manager)
    {

        $entity = new MedicalHistory();
        $form = $this->createForm(MedicalHistoryType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($entity);
            $manager->flush();
            $this->addFlash('sucess', 'Les  antécédents ont été bien enregistrées');

            return $this->redirect($this->generateUrl('medHistory_show', array('id' => $entity->getId())));
        }

        return $this->render('admin/medHistory/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * @Route("medHistory/{id}/edit", name="medHistory_edit", methods={"GET|POST"})
     */
    public function edit(MedicalHistory $medHistory , Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(MedicalHistoryType::class, $medHistory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            //confirmation de la mise à jour
            $this->addFlash('success', 'les Antécèdents ont  Bien été modifiées');
            return $this->redirectToRoute('medHistory_index');
        }

        return $this->render('admin/medHistory/edit.html.twig', [
            'form' => $form->createView(),
            'medHistory' => $medHistory,

        ]);

    }

    /**
     * @Route("/medHistory/{id}/edit", name="medHistory_delete", methods={"DELETE"})
     */
    public function delete(MedicalHistory $medHistory, ObjectManager $manager)
    {
        $manager->remove($medHistory);
        $manager->flush();
        return $this->redirectToRoute('medHistory_index');
    }

    /**
     *  @Route ("medHistory/{id}", name="medHistory_show")
     */
    public function show (MedicalHistory $medHistory)
    {
        return $this->render('admin/medHistory/show.html.twig', [ 'medHistory'=> $medHistory   ] );

    }
}