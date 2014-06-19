<?php

namespace Wa\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wa\FrontBundle\Entity\Idea;

class HomeController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $articleRepo = $em->getRepository('WaFrontBundle:Article');

        // select 5 latest articles :
        $articles = $articleRepo->findBy(
                array('published' => true), array('createDate' => 'ASC'), 5);

        // TODO : on affiche juste la semaine précédente et suivante ? ou bien on rend 
        // dynamique pour afficher toutes les précédentes et toutes les suivantes ?
        // (pour cela il faudrait faire du ajax ... ?)
        $indexThemes = $em->getRepository('WaFrontBundle:Theme')->getIndexThemes();

        // select today top ideas
        $topIdeas = $em->getRepository('WaFrontBundle:Idea')->getTodayTopIdea(4);

        return $this->render('WaFrontBundle:Home:index.html.twig', array(
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
        

        return $this->render('WaFrontBundle:Home:consultation.html.twig', array(
                    'themeSemaine' => $themeSemaine,
                    'todayTopIdeas' => $todayTopIdeas,
                    'weekTopIdeas' => $weekTopIdeas,
        ));
    }

    public function searchAction() {
        $em = $this->getDoctrine()->getManager();

        $disciplines = $em->getRepository('WaFrontBundle:Discipline')->findAll();
        $themes = $em->getRepository('WaFrontBundle:Theme')->getPrevThemes(15);

        return $this->render('WaFrontBundle:Home:search.html.twig', array(
                    'disciplines' => $disciplines,
                    'themes' => $themes
        ));
    }

    /*
     * Handle a Post request with JSON content with this array structure:
     * { 'text': 'TEXT' }
     * for more info about client side : http://www.gillesgallais.com/autocomplete-sur-symfony2/
     */

    public function tagAutocompleteAction(Request $request) {
        // Si requête POST, c'est que l'utilisateur a saisie une recherche :
        if ($request->getMethod() == 'POST') {
            $params = array();
            $content = $this->get("request")->getContent();
            if (!empty($content)) {
                $params = json_decode($content, true); // 2nd param to get as array
            }

            if ($params && array_key_exists('text', $params)) {
                $em = $this->getDoctrine()->getManager();
                $tagsResult = $em->getRepository('WaFrontBundle:Tag')
                        ->findAutocompleteTitles($params['text']);

                // Building HTTP Response :
                $response = new JsonResponse();
                $response->setData($tagsResult);
                return $response;
            } else {
                $response = new JsonResponse();
                $response->setData(array('err' => 'missing parameters'));
                $response->setStatusCode(400);
                return $response;
            }
        }

        // Si on arrive ici ce n'est pas normal ! mais on affiche la page normal :
        $response = new JsonResponse();
        $response->setData(array('err' => 'must be post'));
        $response->setStatusCode(400);
        return $response;
    }

    /*
     * Handle a Post request with JSON content with this array structure:
     * { 'discipline': 'ID', 'theme': 'ID', tags: ['title1', 'title2', ...] }
     */

    public function searchJsonAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        // Si requête POST, c'est que l'utilisateur a saisie une recherche :
        if ($request->getMethod() == 'POST') {
            $params = array();
            $content = $this->get("request")->getContent();
            if (!empty($content)) {
                $params = json_decode($content, true); // 2nd param to get as array
            }


            if ($params &&
                    array_key_exists('discipline', $params) &&
                    array_key_exists('theme', $params) &&
                    array_key_exists('tags', $params)) {
                $tags = new \Doctrine\Common\Collections\ArrayCollection();
                //Checking if tags already exists and replacing by existing ones :
                foreach ($params['tags'] as $tag) {
                    $existingTag = $em->getRepository('WaFrontBundle:Tag')->findOneByTitle($tag['title']);
                    if ($existingTag) {
                        $tags->add($tag);
                    }
                }

                $ideas = $em->getRepository('WaFrontBundle:Idea')->searchIdeas(
                        $em->getRepository('WaFrontBundle:Discipline')->find($params['discipline']), $em->getRepository('WaFrontBundle:Theme')->find($params['theme']), $tags
                );

                // Building Json Data :
                // TODO : edit annotations in every entity definition to chose which attributes to send
                $serializer = $this->container->get('serializer');
                $jsonData = $serializer->serialize($ideas, 'json');

                // Building HTTP Response :
                $response = new Response();
                $response->setContent($jsonData); // Output: {"name":"foo","age":99});
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            } else {
                $response = new JsonResponse();
                $response->setData(array('err' => 'missing parameters'));
                $response->setStatusCode(400);
                return $response;
            }
        }

        // Si on arrive ici ce n'est pas normal ! mais on affiche la page normal :
        $response = new JsonResponse();
        $response->setData(array('err' => 'must be post'));
        $response->setStatusCode(400);
        return $response;
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

    public function consultIdeaAction($idIdea) {
        $em = $this->getDoctrine()->getManager();
        $idea = $em->getRepository('WaFrontBundle:Idea')->findOneById($idIdea);
        $uploads = $idea->getUploads();
        $voteNumber = $idea->getVotesCount();
        return $this->render('WaFrontBundle:Home:consultationIdea.html.twig', array(
                    'idea' => $idea,
                    'imageIdea' => $uploads,
                    'vote' => $voteNumber,
        ));
    }

    public function FAQAction() {
        return $this->render('WaFrontBundle:Static:FAQ.html.twig');
    }

    public function identityAction() {
        return $this->render('WaFrontBundle:Static:identity.html.twig');
    }

    public function rulesAction() {
        return $this->render('WaFrontBundle:Static:rules.html.twig');
    }

}
