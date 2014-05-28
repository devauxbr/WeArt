<?php

namespace Wa\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Wa\FrontBundle\Entity\Theme;
use \Wa\FrontBundle\Entity\Article;
use Wa\AdminBundle\Form\ThemeType;
use Wa\AdminBundle\Form\ArticleType;

class DefaultController extends Controller {

    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name) {
        return array('name' => $name);
    }
    
    public function addArticleAction() {
        $article = new article();
        $form = $this->createForm(new ArticleType, $article);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                /* TODO modifier le retour */
                return $this->redirect($this->generateUrl('sdzblog_accueil'));
            }
        }

        return $this->render('WaAdminBundle:Default:addArticle.html.twig', array(
                    'form' => $form->createView(),
        ));
    }
    
    public function addThemeAction() {
        $theme = new theme();
        $form = $this->createForm(new ThemeType, $theme);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($theme);
                $em->flush();

                /* TODO modifier le retour */
                return $this->redirect($this->generateUrl('sdzblog_accueil'));
            }
        }

        return $this->render('WaAdminBundle:Default:addTheme.html.twig', array(
                    'form' => $form->createView(),
        ));
    }


}
