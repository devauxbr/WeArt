<?php

namespace Wa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wa\FrontBundle\Entity\Article;

class HomeController extends Controller
{
    public function indexAction()
    {
		
		$em = $this->getDoctrine()->getManager();
		$articleRepo = $em->getRepository('WaFrontBundle:Article');
		
		$articles = $articleRepo->findAll();
		
        return $this->render('WaFrontBundle:Home:index.html.twig',
				array('articles' => $articles));
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
}
