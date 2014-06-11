<?php

namespace Wa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wa\FrontBundle\Entity\Article;
use Wa\FrontBundle\Entity\Discipline;
use Wa\FrontBundle\Entity\Idea;
use Wa\MemberBundle\Entity\Account;
use Wa\FrontBundle\Form\IdeaSearchType;

class HomeController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $articleRepo = $em->getRepository('WaFrontBundle:Article');
        
        // select 5 latest articles :
        $articles = $articleRepo->findBy(
                array('published' => true),
                array('createDate' => 'ASC'),
                5);
        
        // TODO : on affiche juste la semaine précédente et suivante ? ou bien on rend 
        // dynamique pour afficher toutes les précédentes et toutes les suivantes ?
        // (pour cela il faudrait faire du ajax ... ?)
        $indexThemes = $em->getRepository('WaFrontBundle:Theme')->getIndexThemes();
        
        // select today top ideas
        $topIdeas = $em->getRepository('WaFrontBundle:Idea')->getTodayTopIdea();
        //$topIdeas = $em->getRepository('WaFrontBundle:Idea')->findAll();
        
        return $this->render('WaFrontBundle:Home:index.html.twig',
                array(
                    'articles' => $articles,
                    'themes' => $indexThemes,
                    'topIdeas' => $topIdeas,
                    ));
    }
    
    public function consultationAction() {
        // TODO : quelles limites de selection pour chaque collection d'entité à retourner ?
        $em = $this->getDoctrine()->getManager();
        
        //select current theme
        $themeSemaine = $em->getRepository('WaFrontBundle:Theme')->getCurrentTheme();
        
        $ideaRepo = $em->getRepository('WaFrontBundle:Idea');
        // select today and week top ideas :
        $todayTopIdeas = $ideaRepo->getTodayTopIdea();
        $weekTopIdeas = $ideaRepo->getWeekTopIdea();
        
        return $this->render('WaFrontBundle:Home:consultation.html.twig',
                array(
                    'themeSemaine' => $themeSemaine,
                    'todayTopIdeas' => $todayTopIdeas,
                    'weekTopIdeas' => $weekTopIdeas,
                    ));
    }
    
    public function searchAction() {
        $request = $this->get('request');
        // Si requête POST, c'est que l'utilisateur a saisie une recherche :
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($idea);
                $em->flush();

                /* TODO quel objet renvoyer ? */
                return $this->redirect($this->generateUrl('wa_front_homepage'));
            }
        }
        
        // Sinon c'est qu'on affiche la page pour la première fois :
        $idea = new Idea();
        $form = $this->createForm(new IdeaSearchType, $idea);
        $em = $this->getDoctrine()->getManager();
        
        // TODO
        
        return $this->render('WaFrontBundle:Home:search.html.twig',
                array(
                    'form' => $form->createView(),
                    ));
    }

    public function addIdeaAction() {
        $idea = new Idea();
        $form = $this->createForm(new IdeaType, $idea);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($idea);
                $em->flush();

                /* TODO modifier le retour pour mettre la page de consultation de l'idée crée ! */
                return $this->redirect($this->generateUrl('wa_front_homepage'));
            }
        }

        return $this->render('WaFrontBundle:Idea:addIdea.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

}
