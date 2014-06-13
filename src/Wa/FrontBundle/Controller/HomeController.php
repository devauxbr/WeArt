<?php

namespace Wa\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
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
                array('published' => true), array('createDate' => 'ASC'), 5);

        // TODO : on affiche juste la semaine précédente et suivante ? ou bien on rend 
        // dynamique pour afficher toutes les précédentes et toutes les suivantes ?
        // (pour cela il faudrait faire du ajax ... ?)
        $indexThemes = $em->getRepository('WaFrontBundle:Theme')->getIndexThemes();

        // select today top ideas
        $topIdeas = $em->getRepository('WaFrontBundle:Idea')->getTodayTopIdea();
        //$topIdeas = $em->getRepository('WaFrontBundle:Idea')->findAll();

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
        return $this->render('WaFrontBundle:Home:search.html.twig');
    }
    
    /*
     * Handle a Post request with JSON content with this array structure:
     * { 'text': 'TEXT' }
     * for more info about client side : http://www.gillesgallais.com/autocomplete-sur-symfony2/
     */
    public function tagAutocompleteAction() {
        $request = $this->get('request');
        // Si requête POST, c'est que l'utilisateur a saisie une recherche :
        if ($request->getMethod() == 'POST') {
            $params = array();
            $content = $this->get("request")->getContent();
            if (!empty($content)) {
                $params = json_decode($content, true); // 2nd param to get as array
            } 
            
            if ($params && array_key_exists('text', $params)) {
                $arrayResonse = array();
                $tagsResult = $em->getRepository('WaFrontBundle:Tag')->findAutocompleteTitles($params['text']);

                // Building Json Data :
                $serializer = $this->container->get('serializer');
                $jsonData = $serializer->serialize($ideas, 'json');

                // Building HTTP Response :
                $response = new JsonResponse();
                $response->setData($jsonData); // Output: {"name":"foo","age":99});
                return $response;
            }
            
        }
        return null;
    }

    /*
     * Handle a Post request with JSON content with this array structure:
     * { 'discipline': 'ID', 'theme': 'ID', tags: ['title1', 'title2', ...] }
     */
    public function searchJsonAction() {
        $em = $this->getDoctrine()->getManager();

        $request = $this->get('request');
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
                        $em->getRepository('WaFrontBundle:Discipline')->find($params['discipline']),
                        $em->getRepository('WaFrontBundle:Theme')->find($params['theme']), 
                        $tags
                        );

                // Building Json Data :
                $serializer = $this->container->get('serializer');
                $jsonData = $serializer->serialize($ideas, 'json');

                // Building HTTP Response :
                $response = new JsonResponse();
                $response->setData($jsonData); // Output: {"name":"foo","age":99});
                return $response;
            }
        }
        
        // Si on arrive ici ce n'est pas normal ! mais on affiche la page normal :
        return $this->render('WaFrontBundle:Home:search.html.twig');
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
