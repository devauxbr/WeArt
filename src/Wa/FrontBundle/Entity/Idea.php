<?php

namespace Wa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use \Wa\MemberBundle\Entity\Upload;

/**
 * Idea
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\FrontBundle\Repository\IdeaRepository")
 */
class Idea
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="voteNumber", type="integer")
     */
    private $voteNumber;

    /**
     *
     * @var type Discipline
     * 
     * @ORM\ManyToOne(targetEntity="Wa\FrontBundle\Entity\Discipline", inversedBy="ideas")
     */
    private $discipline;
    
    
    /**
     *
     * @var type tags
     * 
     * @ORM\ManyToMany(targetEntity="Wa\FrontBundle\Entity\Tag", inversedBy="ideas")
     */
    private $tags;
    
    /**
     *
     * @var type theme
     * 
     * @ORM\ManyToOne(targetEntity="Wa\FrontBundle\Entity\Theme", inversedBy="ideas")
     */
    private $theme;
    
    /**
     *
     * @var type uploads
     * 
     * @ORM\OneToMany(targetEntity="Wa\MemberBundle\Entity\Upload", mappedBy="idea")
     */
    private $uploads;
    
    /**
     *
     * @var type artWork
     * 
     * @ORM\OneToOne(targetEntity="Wa\FrontBundle\Entity\ArtWork", mappedBy="idea")
     */
    private $artWork;
    
    /**
     *
     * @var type account
     * 
     * @ORM\ManyToOne(targetEntity="Wa\MemberBundle\Entity\Account", inversedBy="ideas")
     */
    private $account;
    
    /**
     *
     * @var type votes
     * 
     * @ORM\OneToMany(targetEntity="Wa\FrontBundle\Entity\Vote", mappedBy="idea")
     */
    private $votes;

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
     * Set title
     *
     * @param string $title
     * @return Idea
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Idea
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set voteNumber
     *
     * @param integer $voteNumber
     * @return Idea
     */
    public function setVoteNumber($voteNumber)
    {
        $this->voteNumber = $voteNumber;

        return $this;
    }

    /**
     * Get voteNumber
     *
     * @return integer 
     */
    public function getVoteNumber()
    {
        return $this->voteNumber;
    }

    /**
     * Set discipline
     *
     * @param \Wa\FrontBundle\Entity\Discipline $discipline
     * @return Idea
     */
    public function setDiscipline(\Wa\FrontBundle\Entity\Discipline $discipline = null)
    {
        $this->discipline = $discipline;
        
        $discipline->addIdea($this);

        return $this;
    }

    /**
     * Get discipline
     *
     * @return \Wa\FrontBundle\Entity\Discipline 
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uploads = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tags
     *
     * @param \Wa\FrontBundle\Entity\Tag $tags
     * @return Idea
     */
    public function addTag(\Wa\FrontBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Wa\FrontBundle\Entity\Tag $tags
     */
    public function removeTag(\Wa\FrontBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set theme
     *
     * @param \Wa\FrontBundle\Entity\Theme $theme
     * @return Idea
     */
    public function setTheme(\Wa\FrontBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \Wa\FrontBundle\Entity\Theme 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Add uploads
     *
     * @param \Wa\MemberBundle\Entity\Upload $uploads
     * @return Idea
     */
    public function addUpload(\Wa\MemberBundle\Entity\Upload $uploads)
    {
        $this->uploads[] = $uploads;

        return $this;
    }

    /**
     * Remove uploads
     *
     * @param \Wa\MemberBundle\Entity\Upload $uploads
     */
    public function removeUpload(\Wa\MemberBundle\Entity\Upload $uploads)
    {
        $this->uploads->removeElement($uploads);
    }

    /**
     * Get uploads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUploads()
    {
        return $this->uploads;
    }

    /**
     * Set artWork
     *
     * @param \Wa\FrontBundle\Entity\ArtWork $artWork
     * @return Idea
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

    /**
     * Set account
     *
     * @param \Wa\MemberBundle\Entity\Account $account
     * @return Idea
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

    /**
     * Add votes
     *
     * @param \Wa\FrontBundle\Entity\Vote $votes
     * @return Idea
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
}
