<?php
	namespace App\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	//use Symfony\Bundle\HttpFoundation\Request;

	/**
	 * 
	 */
	class DefaultController extends Controller
	{
		/**
		 * @Route("/", name = "index")
		 */

		public function index()
		{
			return new Response("Bienvenue OCI !");
		}

		/**
		 * @Route("/bjr/{name}", name = "bonjour")
		 */
		public function bonjour(string $name)
		{
			/*return new Response("Bonjour, ". $name .'.');*/

			//return $this->render('bonjour.html.twig', ['prenom' => $name]);

			return $this->render('bonjour.html.twig', array('prenom' => $name));
		}
	}
