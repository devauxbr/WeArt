<?php

namespace Wa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wa\FrontBundle\Entity\Article;
use Wa\FrontBundle\Entity\Discipline;
use Wa\FrontBundle\Entity\Idea;
use Wa\MemberBundle\Entity\Account;
use Wa\FrontBundle\Form\IdeaType;

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
        
        // select week's theme
        $themeSemaine = $em->getRepository('WaFrontBundle:Theme')->findOneByStartDate();
        
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
