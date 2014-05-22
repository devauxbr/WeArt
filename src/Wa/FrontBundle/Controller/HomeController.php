<?php

namespace Wa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wa\FrontBundle\Entity\Article;
use Wa\FrontBundle\Entity\Discipline;
use Wa\FrontBundle\Entity\Idea;

class HomeController extends Controller
{
	public function listArticleAction()
    {
		
		$em = $this->getDoctrine()->getManager();
		$articleRepo = $em->getRepository('WaFrontBundle:Article');
		
		$articles = $articleRepo->findAll();
		
        return $this->render('WaFrontBundle:Home:listArticle.html.twig',
				array('articles' => $articles));
    }
	
    public function indexAction()
    {
		return $this->render('WaFrontBundle:Home:index.html.twig');
    }
	
    public function addArticleAction()
    {
        $article = new Article();
        $article->setTitle('Une autre news');
        $article->setContent('Le contenu');
        $article->setCreateDate(new \DateTime());
        $article->setEditDate(new \DateTime());
        $article->setPublished(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->render('WaFrontBundle:Home:addArticle.html.twig');
    }
    
    public function testAction()
    {
        $idea = new Idea();
        $idea->setTitle('The title');
        $idea->setDescription('dqfsdfjsfksdfjksdf');
        $idea->setVoteNumber(0);
        
        $discipline = new Discipline();
        $discipline->setTitle('sdfhsdfohzi');
        $discipline->setDescription('Something');
        
        $idea->setDiscipline($discipline);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($discipline);
        $em->persist($idea);
        $em->flush();
        
        return $this->render('WaFrontBundle:Home:addArticle.html.twig');
    }
}
