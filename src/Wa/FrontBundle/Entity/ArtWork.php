<?php

namespace Wa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Wa\MemberBundle\Entity\Account;
use JMS\Serializer\Annotation as JMS;

/**
 * ArtWork
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\FrontBundle\Repository\ArtWorkRepository")
 */
class ArtWork
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
     * @var boolean
     *
     * @ORM\Column(name="fulfil", type="boolean")
     */
    private $fulfil;
    
    /**
     *
     * @var type Idea
     * 
     * @ORM\OneToOne(targetEntity="Wa\FrontBundle\Entity\Idea", mappedBy="artWork")
     * @ORM\JoinColumn(name="idea_id",referencedColumnName="id", nullable=true)
     */
    private $idea;
    
    /**
     *
     * @var type Account
     * 
     * @ORM\OneToOne(targetEntity="Wa\MemberBundle\Entity\Account", inversedBy="artWork")
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
     * Set fulfil
     *
     * @param boolean $fulfil
     * @return ArtWork
     */
    public function setFulfil($fulfil)
    {
        $this->fulfil = $fulfil;

        return $this;
    }

    /**
     * Get fulfil
     *
     * @return boolean 
     */
    public function getFulfil()
    {
        return $this->fulfil;
    }

    /**
     * Set idea
     *
     * @param \Wa\FrontBundle\Entity\Idea $idea
     * @return ArtWork
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
     * @return ArtWork
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
