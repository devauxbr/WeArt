<?php

namespace Wa\FrontBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Wa\FrontBundle\Entity\Tag;
use Wa\FrontBundle\Entity\Theme;

/**
 * IdeaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IdeaRepository extends EntityRepository {

    public function getTodayTopIdea() {
        $date = new \Datetime();

        $qb = $this->createQueryBuilder('i')
                ->select('i, count(v.id) AS HIDDEN nbVotes')
                ->join('i.votes', 'v')
                ->where('v.date >= :date')->setParameter('date', $date->sub(\DateInterval::createFromDateString('1 days')))
                ->orderby('nbVotes', 'DESC')
                ->groupBy('v.idea');

        return $qb->getQuery()->getResult();
    }

    public function getWeekTopIdea() {
        $date = new \Datetime();

        $qb = $this->createQueryBuilder('i')
                ->select('i, count(v.id) AS HIDDEN nbVotes')
                ->join('i.votes', 'v')
                ->where('v.date >= :date')->setParameter('date', $date->sub(\DateInterval::createFromDateString('1 weeks')))
                ->orderby('nbVotes', 'DESC')
                ->groupBy('v.idea');
        
        return $qb->getQuery()->getResult();
    }
    
    public function searchIdea($discipline, $theme, $tags) {
        return $this->findAll();
    }

}
