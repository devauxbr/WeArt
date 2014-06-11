<?php

namespace Wa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Tag
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\FrontBundle\Repository\TagRepository")
 */
class Tag
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
     *
     * @var type Ideas
     * 
     * @ORM\ManyToMany(targetEntity="Wa\FrontBundle\Entity\Idea", mappedBy="tags")
     */
    private $ideas;


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
     * @return Title
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
     * Constructor
     */
    public function __construct()
    {
        $this->ideas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ideas
     *
     * @param \Wa\FrontBundle\Entity\Idea $ideas
     * @return Tag
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
}
