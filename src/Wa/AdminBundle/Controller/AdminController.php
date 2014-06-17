<?php

namespace Wa\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wa\FrontBundle\Entity\Theme;
use Wa\FrontBundle\Entity\Article;
use Wa\FrontBundle\Entity\Discipline;
use Wa\FrontBundle\Entity\Vote;
use Wa\FrontBundle\Entity\Idea;
use Wa\FrontBundle\Entity\Tag;
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

	public function listThemeAction() {
		$em = $this->getDoctrine()->getManager();
		$themes = $em->getRepository('WaFrontBundle:Theme')->findAll();

		return $this->render('WaAdminBundle:Default:listThemes.html.twig', array(
					'themes' => $themes,
		));
	}

	private function execCommand($options) {
		$kernel = $this->get('kernel');
		$application = new Application($kernel);
		$application->setAutoExit(false);
		$result = $application->run(new ArrayInput($options));

		if ($result !== 0) {
			//exit('Comman ' . $options['command'] . ' failed !');
		}
	}

	private function resetEm() {
		$em = $this->getDoctrine()->getManager();
		$em->close();
		$em = $em->create(
				$em->getConnection(), $em->getConfiguration()
		);

		return $em;
	}

	public function clearSiteAction() {
		// Drop DB
		$this->execCommand(array('command' => 'doctrine:database:drop', "--force" => true));

		// Create DB
		$this->execCommand(array('command' => 'doctrine:database:create'));

		return $this->render('WaAdminBundle:Default:clearSite.html.twig');
	}

	public function fillSiteAction() {
		// Update schema
		$this->execCommand(array('command' => 'doctrine:schema:create'));
		
		set_time_limit(0);

		// Enable garbage collector
        //gc_enable();
		
		
		$em = $this->getDoctrine()->getManager();

		// Disalbe SQL logger
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
		
		// --------------> Reliable data source	
		$loremIpsum = $this->get('apoutchika.lorem_ipsum');

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
		$users = array();
		
		$userManager = $this->get('fos_user.user_manager');
		$user = $userManager->createUser();

		$user->setUsername('colin');
		$user->setEmail('congelli501@gmail.com');
		$user->setEnabled(true);
		$user->setPlainPassword('colin');
		$users[] = $user;

		$em->persist($user);

		$user = $userManager->createUser();

		$user->setUsername('pauline');
		$user->setEmail('p.beurier@gmail.com');
		$user->setEnabled(true);
		$user->setPlainPassword('pauline');
		$users[] = $user;
		
		$em->persist($user);

		$user = $userManager->createUser();

		$user->setUsername('bruno');
		$user->setEmail('devaux.br@gmail.com');
		$user->setEnabled(true);
		$user->setPlainPassword('bruno');
		$users[] = $user;

		$em->persist($user);

		$user = $userManager->createUser();

		$user->setUsername('sylvain');
		$user->setEmail('sylvain.lagache.92@gmail.com');
		$user->setEnabled(true);
		$user->setPlainPassword('sylvain');
		$users[] = $user;

		$em->persist($user);

		// --------------> Discipline

		$discplines = array();

		$discpline = new Discipline();
		$discpline->setTitle('Dessin');
		$discpline->setDescription('Un peu de dessin');
		$discpline->setLogoName('dessin.png');
		$em->persist($discpline);
		$discplines[] = $discpline;

		$discpline = new Discipline();
		$discpline->setTitle('Infographisme');
		$discpline->setDescription('Du dessin, mais sur un ordinateur');
		$discpline->setLogoName('infographisme.png');
		$em->persist($discpline);
		$discplines[] = $discpline;

		$discpline = new Discipline();
		$discpline->setTitle('Litterature');
		$discpline->setDescription('Nouvelles, romans...');
		$discpline->setLogoName('litterature.png');
		$em->persist($discpline);
		$discplines[] = $discpline;

		$discpline = new Discipline();
		$discpline->setTitle('Peinture');
		$discpline->setDescription('À l\'eau, à l\'huile, il y en a pour tous les goûts !');
		$discpline->setLogoName('peinture.png');
		$em->persist($discpline);
		$discplines[] = $discpline;

		$discpline = new Discipline();
		$discpline->setTitle('Photo');
		$discpline->setDescription('Prenez les plus beau clichés');
		$discpline->setLogoName('photo.png');
		$em->persist($discpline);
		$discplines[] = $discpline;

		$discpline = new Discipline();
		$discpline->setTitle('Sculture');
		$discpline->setDescription('L\'art en 3D !');
		$discpline->setLogoName('sculpture.png');
		$em->persist($discpline);
		$discplines[] = $discpline;

		$discpline = new Discipline();
		$discpline->setTitle('Video');
		$discpline->setDescription('Réalisez vos courts métrages');
		$discpline->setLogoName('video.png');
		$em->persist($discpline);
		$discplines[] = $discpline;
		
		// --------------> Tags
		$tagCount = rand(10, 20);
		$tagArray = array();
		for($i = 0; $i < $tagCount; $i++)
		{
			$tag = new Tag();
			$tag->setTitle($loremIpsum->getWords(1));
			
			$em->persist($tag);
			
			$tagArray[] = $tag;
		}

		// --------------> Themes

		for ($i = 0; $i < 5; $i++) {
			$theme = new Theme();
			$theme->setTitle('Le rève n°' . $i);
			$theme->setDescription('Montrer vos rèves (édition ' . $i . ')');
			$theme->setYear(2014);
			$theme->setWeek(22 + $i);
			$em->persist($theme);

			// --------------> Ideas
			$ideaCount = rand(10, 60);
			for ($j = 0; $j < $ideaCount; $j++) {
				$idea = new Idea();
				$idea->setTitle($loremIpsum->getWords(2, 4));
				$idea->setDescription($loremIpsum->getParagraphs(2, 6));
				$idea->setDiscipline($discplines[rand(0, count($discplines) - 1)]);
				$idea->setTheme($theme);
				$idea->setAccount($users[rand(0, count($users) - 1)]);
				
				// Tags
				$tagForIdea = rand(0, 5);
				$givenTags = array();
				for($k = 0; $k < $tagForIdea; $k++)
				{
					$tagPos = rand(0, count($tagArray) - 1);
					if(! array_key_exists($tagPos, $givenTags))
					{
						$tag = $tagArray[$tagPos];
						$idea->addTag($tag);
						
						$givenTags[$tagPos] = true;
					}
				}
				
				
				
				$voteCount = rand(1, 500);
				$idea->setVoteNumber($voteCount);
				for($k = 0; $k < $voteCount; $k++)
				{
					$vote = new Vote();
					
					$vote->setDate(new \DateTime());
					$vote->setAccount($users[rand(0, count($users) - 1)]);
					$vote->setIdea($idea);
					
					$em->persist($vote);
				}
				
				$em->persist($idea);
			}
			
			
			$em->flush();
		}

		
		$em->flush();
		// --------------> Ideas

		return $this->render('WaAdminBundle:Default:fillSite.html.twig');
	}

}
