<?php

namespace App\Controller;


use App\Entity\MedicalHistory;
use App\Form\MedicalHistoryType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicalHistoryController extends AbstractController
{

      /**
     * @Route("/medHistory/", name="medHistory_index")
     */
    public function indexAction(): Response
    {
        return $this->render('admin/MedHistory/index.html.twig');
    }

	/**
	 * @Route("/medHistory/new", name="medHistory_new")
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @return RedirectResponse|Response
	 */
    public function newAction(Request $request, ObjectManager $manager)
    {

        $entity = new MedicalHistory();
        $form = $this->createForm(MedicalHistoryType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($entity);
            $manager->flush();
            $this->addFlash('success', 'Les  antécédents ont été bien enregistrés');

            return $this->redirect($this->generateUrl('medHistory_show', array('id' => $entity->getId())));
        }

        return $this->render('admin/medHistory/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

	/**
	 * @Route("medHistory/{id}/edit", name="medHistory_edit", methods={"GET|POST"})
	 * @param MedicalHistory $medHistory
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @return RedirectResponse|Response
	 */
    public function editAction(MedicalHistory $medHistory , Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(MedicalHistoryType::class, $medHistory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            //confirmation de la mise à jour
            $this->addFlash('success', 'Les antécédents ont  bien été modifiés');
            return $this->redirectToRoute('medHistory_index');
        }
        return $this->render('admin/medHistory/edit.html.twig', [
            'form' => $form->createView(),
            'medHistory' => $medHistory,

        ]);

    }

	/**
	 * @Route("/medHistory/{id}/edit", name="medHistory_delete", methods={"DELETE"})
	 * @param MedicalHistory $medHistory
	 * @param ObjectManager $manager
	 * @return RedirectResponse
	 */
    public function deleteAction(MedicalHistory $medHistory, ObjectManager $manager): RedirectResponse
    {
        $manager->remove($medHistory);
        $manager->flush();
        return $this->redirectToRoute('medHistory_index');
    }

	/**
	 * @Route ("medHistory/{id}", name="medHistory_show")
	 * @param MedicalHistory $medHistory
	 * @return Response
	 */
    public function showAction (MedicalHistory $medHistory): Response
    {
        return $this->render('admin/medHistory/show.html.twig', [ 'medHistory'=> $medHistory   ] );

    }
}