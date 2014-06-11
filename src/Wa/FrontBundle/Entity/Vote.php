<?php

namespace Wa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Vote
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\FrontBundle\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    
     /**
     *
     * @var type Idea
     * 
     * @ORM\ManyToOne(targetEntity="Wa\FrontBundle\Entity\Idea", inversedBy="votes")
     */
    private $idea;
    
     /**
     *
     * @var type Account
     * 
     * @ORM\ManyToOne(targetEntity="Wa\MemberBundle\Entity\Account", inversedBy="votes")
     */
    private $account;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Vote
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set idea
     *
     * @param \Wa\FrontBundle\Entity\Idea $idea
     * @return Vote
     */
    public function setIdea(\Wa\FrontBundle\Entity\Idea $idea = null)
    {
        $this->idea = $idea;

        return $this;
    }

    /**
     * Get idea
     *
     * @return \Wa\FrontBundle\Entity\Idea 
     */
    public function getIdea()
    {
        return $this->idea;
    }

    /**
     * Set account
     *
     * @param \Wa\MemberBundle\Entity\Account $account
     * @return Vote
     */
    public function setAccount(\Wa\MemberBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Wa\MemberBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
}
