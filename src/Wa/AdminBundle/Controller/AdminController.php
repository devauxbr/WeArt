<?php

namespace Wa\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

                return $this->redirect($this->generateUrl('wa_admin_homepage'));
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

                return $this->redirect($this->generateUrl('wa_admin_homepage'));
            }
        }

        return $this->render('WaAdminBundle:Default:addTheme.html.twig', array(
                    'form' => $form->createView(),
        ));
    }
}
