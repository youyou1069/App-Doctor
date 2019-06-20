<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 * pas de condition d'accès a cette page
	 */
	public function homeAction(): Response
	{
		return $this->render('home.html.twig');

	}
}
