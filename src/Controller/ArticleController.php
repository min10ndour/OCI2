<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function create(Request $request)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
        ->add('titre', TextType::class)
        ->add('description', TextType::class)
        ->add('contenu', TextType::class)
        ->add('auteur', TextType::class)
        ->add('creer', SubmitType::class)
        ->getForm();

        $form->handleRequest($request );

    	if ($form->isSubmitted() && $form->isValid()) {

            $titre = $form['titre']->getData();
            $description = $form['description']->getData();
            $contenu = $form['contenu']->getData();
            $auteur = $form['auteur']->getData();

            $article->setTitre($titre/*$_POST['titre']*/);
            $article->setDate(new \DateTime());
            $article->setDescription($description/*$_POST['descript']*/);
            $article->setContenu($contenu/*$_POST['contenu']*/);
            $article->setAuteur($auteur/*$_POST['auteur']*/);
            $article->setLastModif(new \DateTime());

        //First of all, you need the entity manager of doctrine
            $em = $this->getDoctrine()->getManager();

        //INSERT
            $em->persist($article);

        //FLUSH VALIDE L'INSERTION
            $em->flush();

            return $this->redirectToRoute('article');
        }else{
            return $this->render('article/creer2.html.twig', array('creerForm' => $form->createView()));
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

            //UTILISER METHODE FORM CREATOR

            $article->setTitre(/*$_POST['titre']*/);
            $article->setDate(new \DateTime($_POST['date']));
            $article->setDescription($_POST['descript']);
            $article->setContenu($_POST['contenu']);
            $article->setAuteur($_POST['auteur']);
            $article->setLastModif(new \DateTime());
            
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
