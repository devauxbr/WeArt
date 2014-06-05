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
        return $this->render('WaFrontBundle:Home:index.html.twig');
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

                /* TODO modifier le retour */
                return $this->redirect($this->generateUrl('sdzblog_accueil'));
            }
        }

        return $this->render('WaFrontBundle:Idea:addIdea.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

}
