<?php

namespace Wa\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wa\FrontBundle\Entity\Idea;
use Wa\FrontBundle\Entity\Vote;
use Wa\MemberBundle\Form\IdeaType;

class MemberController extends Controller {

    public function addIdeaAction() {
        $idea = new Idea();
        $form = $this->createForm(new IdeaType, $idea);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //get current theme :
                $currentTheme = $em->getRepository('WaFrontBundle:Theme')->getCurrentTheme();
                //Manually set user account, and current theme to the new idea:
                $idea->setAccount($this->getUser());
                $idea->setTheme($currentTheme);

                //Manually set uploads links for this idea (why manually ? don't know but it works...)
                foreach ($idea->getUploads() as $upload) {
                    $idea->addUpload($upload);
                }

                //Checking if tags already exists and replacing by existing ones :
                foreach ($idea->getTags() as $tag) {
                    $existingTag = $em->getRepository('WaFrontBundle:Tag')->findOneByTitle($tag->getTitle());
                    if ($existingTag) {
                        $idea->removeTag($tag);
                        $idea->addTag($existingTag);
                    }
                }

                // persist new idea in BDD :
                $em->persist($idea);
                $em->flush();

                /* TODO modifier le retour pour mettre la page de consultation de l'idée crée ! */
                return $this->redirect($this->generateUrl('wa_front_homepage'));
            }
        }

        return $this->render('WaMemberBundle:Idea:addIdea.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function addVoteIdeaAction($idIdea) {
        $em = $this->getDoctrine()->getManager();
        $idea = $em->getRepository('WaFrontBundle:Idea')->findOneById($idIdea);
        $vote = new Vote($idea, $this->getUser());
        $em->persist($vote);
        $em->flush();
        /* TODO modifier le retour  */
        return $this->redirect($this->generateUrl('wa_front_homepage'));
    }

}
