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
	
	private function getRandomDate()
	{
		$date = new \DateTime();
		$toSub = new \DateInterval('PT0S');
		$toSub->y = 0;
		$toSub->m = 0;
		$toSub->d = rand(0, 30);
		$toSub->h = rand(0, 24);
		$toSub->i = rand(0, 60);
		$toSub->s = rand(0, 60);

		$date->sub($toSub);
		
		return $date;
	}
	
	public function fillSiteAction() {
		// Update schema
		$this->execCommand(array('command' => 'doctrine:schema:create'));
		
		gc_enable();
		
		set_time_limit(0);

		// Enable garbage collector
        //gc_enable();
		
		
		$em = $this->getDoctrine()->getManager();

		// Disalbe SQL logger
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
		
		// --------------> Reliable data source	
		$loremIpsum = $this->get('apoutchika.lorem_ipsum');

		// --------------> Article
		for($i = 0; $i < 16; $i++)
		{
			$article = new Article();
			$article->setTitle($loremIpsum->getWords(3, 7));
			$article->setContent($loremIpsum->getParagraphs(1));
			$article->setCreateDate($this->getRandomDate());
			$article->setEditDate($this->getRandomDate());
			$article->setPublished(true);
			$em->persist($article);
		}

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
		$tagCount = rand(15, 30);
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
			$theme->setTitle($loremIpsum->getWords(1, 4));
			$theme->setDescription($loremIpsum->getWords(10, 20));
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
					
					$vote->setDate($this->getRandomDate());
					$vote->setAccount($users[rand(0, count($users) - 1)]);
					$vote->setIdea($idea);
					
					$em->persist($vote);
				}
				
				$em->persist($idea);
			}
			
			gc_collect_cycles();
			$em->flush();
		}

		gc_collect_cycles();
		$em->flush();
		// --------------> Ideas

		return $this->render('WaAdminBundle:Default:fillSite.html.twig');
	}

}
