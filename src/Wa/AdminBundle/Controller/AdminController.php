<?php

namespace Wa\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Wa\FrontBundle\Entity\Theme;
use \Wa\FrontBundle\Entity\Article;
use Wa\AdminBundle\Form\ThemeType;
use Wa\AdminBundle\Form\ArticleType;

class AdminController extends Controller {

    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction() {
        return $this->render('WaAdminBundle:Default:index.html.twig');
    }
    
    public function addArticleAction() {
        $article = new Article();
        $form = $this->createForm(new ArticleType, $article);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                // On définit un message flash
                $this->get('session')->getFlashBag()->add('info', 'Article bien ajouté');


                return $this->redirect($this->generateUrl('wa_admin_homepage'));
            }
        }

        return $this->render('WaAdminBundle:Default:addArticle.html.twig', array(
                    'form' => $form->createView(),
        ));
    }
    
    public function addThemeAction() {
        $theme = new Theme();
        $form = $this->createForm(new ThemeType, $theme);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($theme);
                $em->flush();
                // On définit un message flash
                $this->get('session')->getFlashBag()->add('info', 'Thème bien ajouté');


                return $this->redirect($this->generateUrl('wa_admin_homepage'));
            }
        }

        return $this->render('WaAdminBundle:Default:addTheme.html.twig', array(
                    'form' => $form->createView(),
        ));
    }
	
	private function execCommand($options) {
		$kernel = $this->get('kernel');
		$application = new Application($kernel);
		$application->setAutoExit(false);
		$result = $application->run(new ArrayInput($options));
		
		if($result !== 0)
		{
			//exit('Comman ' . $options['command'] . ' failed !');
		}
	}
	
	private function resetEm() {
		$em = $this->getDoctrine()->getManager();
		$em->close();
		$em = $em->create(
			$em->getConnection(),
			$em->getConfiguration()
		);
		
		return $em;
	}
	
	public function clearSiteAction()
	{
		// Drop DB
		$this->execCommand(array('command' => 'doctrine:database:drop',"--force" => true));
		
		// Create DB
		$this->execCommand(array('command' => 'doctrine:database:create'));
		
		return $this->render('WaAdminBundle:Default:clearSite.html.twig');
	}
	
	public function fillSiteAction() {
		// Update schema
		$this->execCommand(array('command' => 'doctrine:schema:create'));
		
		// Reset entity manager
		$em = $this->getDoctrine()->getManager();
		/*$em->close();
		$em = $em->create(
			$em->getConnection(),
			$em->getConfiguration()
		);*/

		// --------------> Article
		$article = new Article();
		$article->setTitle("Une news");
		$article->setContent("Proin nonummy, sit amet eros. Nullam ornare. Praesent odio ligula, dapibus sed, tincidunt eget, dictum ac, nibh. Nam quis lacus. Nunc eleifend molestie velit. Morbi lobortis quam eu velit. Donec euismod vestibulum massa. Donec non lectus. Aliquam commodo lacus sit amet nulla. Cras dignissim elit et augue. Nullam non diam.");
		$article->setCreateDate(new \DateTime("2014-06-01 11:14:15"));
		$article->setEditDate(new \DateTime("2014-06-01 11:14:15"));
		$article->setPublished(true);
		$em->persist($article);

		$article = new Article();
		$article->setTitle("Une autre news");
		$article->setContent(" Nullam non diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In hac habitasse platea dictumst. Aenean vestibulum. Nullam ornare. ");
		$article->setCreateDate(new \DateTime("2014-06-01 11:14:15"));
		$article->setEditDate(new \DateTime("2014-06-01 11:14:15"));
		$article->setPublished(true);
		$em->persist($article);
		
		$article = new Article();
		$article->setTitle("Encore une autre news");
		$article->setContent(" Lorem ipsum dolor sit amet, aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident mollit anim id est laborum. ");
		$article->setCreateDate(new \DateTime("2014-06-01 11:14:15"));
		$article->setEditDate(new \DateTime("2014-06-01 11:14:15"));
		$article->setPublished(true);
		$em->persist($article);
		
		// --------------> Users
		$userManager = $this->get('fos_user.user_manager');
		$user = $userManager->createUser();
		
		$user->setUsername('colin');
		$user->setEmail('congelli501@gmail.com');
		$user->setEnabled(true);
		$user->setPlainPassword('colin');
		
		$em->persist($user);
		
		$user = $userManager->createUser();
		
		$user->setUsername('pauline');
		$user->setEmail('p.beurier@gmail.com');
		$user->setEnabled(true);
		$user->setPlainPassword('pauline');
		
		$em->persist($user);
		
		$user = $userManager->createUser();
		
		$user->setUsername('bruno');
		$user->setEmail('devaux.br@gmail.com');
		$user->setEnabled(true);
		$user->setPlainPassword('bruno');
		
		$em->persist($user);
		
		// --------------> Themes
		
		
		$em->flush();
		
		return $this->render('WaAdminBundle:Default:fillSite.html.twig');
	}
}
