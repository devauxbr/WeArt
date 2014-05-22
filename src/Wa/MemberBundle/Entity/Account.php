<?php

namespace Wa\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Wa\FrontBundle\Entity\ArtWork;

/**
 * Account
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\MemberBundle\Repository\AccountRepository")
 */
class Account
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
     * @var string
     *
     * @ORM\Column(name="pseudonym", type="string", length=255)
     */
    private $pseudonym;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var integer
     *
     * @ORM\Column(name="point", type="integer")
     */
    private $point;

    /**
     * @var integer
     *
     * @ORM\Column(name="autorisation", type="integer")
     */
    private $autorisation;

    
    /**
     *
     * @var type Ideas
     * 
     * @ORM\OneToMany(targetEntity="Wa\FrontBundle\Entity\Idea", mappedBy="account")
     */
    private $ideas;
    
    /**
     *
     * @var type Votes
     * 
     * @ORM\OneToMany(targetEntity="Wa\FrontBundle\Entity\Vote", mappedBy="accounts")
     */
    private $votes;

    /**
     *
     * @var type Article
     * 
     * @ORM\OneToMany(targetEntity="Wa\FrontBundle\Entity\Article", mappedBy="accounts")
     */
    private $article;
    
     /**
     *
     * @var type ArtWork
     * 
     * @ORM\OneToOne(targetEntity="Wa\FrontBundle\Entity\ArtWork", inversedBy="accounts")
     * @ORM\JoinColumn(name="art_work_id",referencedColumnName="id", nullable=true)
     */
    private $artWork;
    
    
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
     * Set pseudonym
     *
     * @param string $pseudonym
     * @return Account
     */
    public function setPseudonym($pseudonym)
    {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    /**
     * Get pseudonym
     *
     * @return string 
     */
    public function getPseudonym()
    {
        return $this->pseudonym;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Account
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set point
     *
     * @param integer $point
     * @return Account
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set autorisation
     *
     * @param integer $autorisation
     * @return Account
     */
    public function setAutorisation($autorisation)
    {
        $this->autorisation = $autorisation;

        return $this;
    }

    /**
     * Get autorisation
     *
     * @return integer 
     */
    public function getAutorisation()
    {
        return $this->autorisation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ideas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ideas
     *
     * @param \Wa\FrontBundle\Entity\Idea $ideas
     * @return Account
     */
    public function addIdea(\Wa\FrontBundle\Entity\Idea $ideas)
    {
        $this->ideas[] = $ideas;

        return $this;
    }

    /**
     * Remove ideas
     *
     * @param \Wa\FrontBundle\Entity\Idea $ideas
     */
    public function removeIdea(\Wa\FrontBundle\Entity\Idea $ideas)
    {
        $this->ideas->removeElement($ideas);
    }

    /**
     * Get ideas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdeas()
    {
        return $this->ideas;
    }

    /**
     * Add votes
     *
     * @param \Wa\FrontBundle\Entity\Vote $votes
     * @return Account
     */
    public function addVote(\Wa\FrontBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Wa\FrontBundle\Entity\Vote $votes
     */
    public function removeVote(\Wa\FrontBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Add article
     *
     * @param \Wa\FrontBundle\Entity\Article $article
     * @return Account
     */
    public function addArticle(\Wa\FrontBundle\Entity\Article $article)
    {
        $this->article[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Wa\FrontBundle\Entity\Article $article
     */
    public function removeArticle(\Wa\FrontBundle\Entity\Article $article)
    {
        $this->article->removeElement($article);
    }

    /**
     * Get article
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set artWork
     *
     * @param \Wa\FrontBundle\Entity\ArtWork $artWork
     * @return Account
     */
    public function setArtWork(\Wa\FrontBundle\Entity\ArtWork $artWork = null)
    {
        $this->artWork = $artWork;

        return $this;
    }

    /**
     * Get artWork
     *
     * @return \Wa\FrontBundle\Entity\ArtWork 
     */
    public function getArtWork()
    {
        return $this->artWork;
    }
}
