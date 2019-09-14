<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
    	/*$articles = array(
    		array('id' => 0, 
    		'titre' => 'Premier Article', 
    		'description' => 'Ceci est le tout premier article', 
    		'auteur' => 'Mindiss'),

    		array('id' => 1, 
    		'titre' => 'Deuxième Article', 
    		'description' => 'Ceci est le second article', 
    		'auteur' => 'Mindiss')
    	);*/
    	$articles = $this->getDoctrine()->getRepository(Article::Class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     *@Route("/single/{{id}}", name="single")
     */
    public function single(int $id)
    {
    	
    }

    /**
     * @Route("/create", name="create")
     */
    public function create()
    {
    	$article = new Article();

    	$article->setTitre('4eme Article');
    	$article->setDate(new \DateTime());
    	$article->setDescription("Ceci est le 4eme article.");
    	$article->setContenu("Bonjour Humains. Ceci est notre 4eme article. Nous sommes là pour vous rencontrer. Bonjour !");
    	$article->setAuteur('Mindiss');

    	//First of all, you need the entity manager of doctrine
    	$em = $this->getDoctrine()->getManager();

    	//INSERT
    	$em->persist($article);

    	//FLUSH VALIDE L'INSERTION
    	$em->flush();

    	return $this->redirectToRoute('article');
    }
}
