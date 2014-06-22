<?php

namespace Wa\FrontBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ThemeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ThemeRepository extends EntityRepository {

    public function getIndexThemes() {
        // current week number :
        $date = new \DateTime();  // today
        $week = (int) $date->format("W");
        
        // select theme from previous, current and next week :
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.week >= :weekMoins')
                ->setParameter('weekMoins', $week - 1)
                ->andWhere('t.week <= :weekPlus')
                ->setParameter('weekPlus', $week + 1)
                ->orderBy('t.week', 'ASC');

        return $qb->getQuery()->getResult();
    }
	
	public function getPrevThemes($count) {
        // current week number :
        $date = new \DateTime();  // today
        $week = (int) $date->format("W");
        
        // select theme from previous, current and next week :
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.week <= :weekPlus')
                ->setParameter('weekPlus', $week)
                ->orderBy('t.week', 'DESC')
				->setMaxResults($count);

        return $qb->getQuery()->getResult();
    }
    
    public function getCurrentTheme() {
        // current week number :
        $date = new \DateTime();  // today
        $week = (int) $date->format("W");
        // select week's theme
        return $this->findOneByWeek($week);
    }
	
	public function getThemesBo($isAll = true) {
		
		$qb = $this->createQueryBuilder('t')
                ->select('t AS theme, count(DISTINCT i.id) AS nbIdea, count(v.id) AS nbVotes')
				->leftJoin('t.ideas', 'i')
                ->leftJoin('i.votes', 'v')
                ->orderBy('t.year', 'ASC')
				->addOrderBy('t.week', 'ASC')
				->groupBy('i')
                ->groupBy('t');
		
		if(! $isAll)
		{
			$week = (int) (new \DateTime())->format("W") - 5;
			$qb->where('t.week >= :week')->setParameter('week', $week);
		}

        return $qb->getQuery()->getResult();
	}

}
