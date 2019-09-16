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
     *@Route("/read/{{id}}", name="read")
     */
    public function read(int $id)
    {
    	$em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository(Article::Class)->find($id);

        $date = $article->getDate()->format('Y-m-d H:i:s');

        $em->flush();

        return $this->render('article/read.html.twig',
            ['article' => $article, 'date' => $date]
        );
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function create()
    {
    	if (isset($_POST['creer'])) {
            $article = new Article();

            $article->setTitre($_POST['titre']);
            $article->setDate(new \DateTime($_POST['date']));
            $article->setDescription($_POST['descript']);
            $article->setContenu($_POST['contenu']);
            $article->setAuteur($_POST['auteur']);
            $article->setLastModif(new \DateTime());

        //First of all, you need the entity manager of doctrine
            $em = $this->getDoctrine()->getManager();

        //INSERT
            $em->persist($article);

        //FLUSH VALIDE L'INSERTION
            $em->flush();

            return $this->redirectToRoute('article');
        }else{
            return $this->render('article/creer.html.twig');
        }
    }

    /**
     *@Route("/del/{{id}}", name="del")
     */
    public function delete(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository(Article::Class)->find($id);
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('article');
    }

    /**
     *@Route("/modif/{{id}}", name="modif")
     */
    public function update(int $id)
    {
        if (isset($_POST['modifier'])) {
            $em = $this->getDoctrine()->getManager();
            $article = $this->getDoctrine()->getRepository(Article::Class)->find($id);
            $em->refresh($article);
            $em->flush();

            return $this->redirectToRoute('read', ['id'  => $id]);
        }else{
            $em = $this->getDoctrine()->getManager();
            $article = $this->getDoctrine()->getRepository(Article::Class)->find($id);

            //$em->flush();

            $date = $article->getDate()->format('Y-m-d H:i:s');

            return $this->render('article/modif.html.twig',
                ['article' => $article, 'date' => $date]
            );
        }
    }
}
