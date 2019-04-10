<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DrugController extends AbstractController
{
    /**
     * @Route("/drug", name="drug")
     */
    public function index()
    {
        return $this->render('drug/index.html.twig', [
            'controller_name' => 'DrugController',
        ]);
    }

	/**
	 * @Route(path="/search", name="drug_search")
	 * @Method({"GET"})
	 */
	public function searchAction(Request $request)
	{
		$searchString = $request->get('q');
		if(strlen($searchString) < 2)
			return new Response("Invalid search query", 406);
		dump($searchString);
		$entityManager = $this->getDoctrine()->getManager();
		$query = $entityManager->createQuery('
            SELECT a FROM App:Drug a
            WHERE a.denomination LIKE :searchString
                OR a.name LIKE :searchString
            ORDER BY a.denomination ASC
       ')
		                       ->setParameter('searchString', '%' . $searchString . '%');

		$drugs = $query->getResult();
		dump($drugs);
		$drugsArray = array();
		foreach($drugs as $drug)
		{
			$drugsArray[] = array(
				'id' => $drug->getId(),
				'text' => $drug->__toString()

			);
		}
		return new Response(json_encode($drugsArray), 200, array('Content-Type' => 'application/json'));
	}
}
