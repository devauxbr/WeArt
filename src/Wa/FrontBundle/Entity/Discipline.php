<?php

namespace Wa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Discipline
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\FrontBundle\Repository\DisciplineRepository")
 */
class Discipline
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     *
     * @var type Ideas
     * 
     * @ORM\OneToMany(targetEntity="Wa\FrontBundle\Entity\Idea", mappedBy="discipline")
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
     * @return Discipline
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
     * @return Discipline
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
     * Constructor
     */
    public function __construct()
    {
        $this->discipline = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add idea
     *
     * @param \Wa\FrontBundle\Entity\Idea $idea
     * @return Discipline
     */
    public function addIdea(\Wa\FrontBundle\Entity\Idea $idea)
    {
        $this->idea[] = $idea;

        return $this;
    }

    /**
     * Remove idea
     *
     * @param \Wa\FrontBundle\Entity\Idea $idea
     */
    public function removeIdea(\Wa\FrontBundle\Entity\Idea $idea)
    {
        $this->idea->removeElement($idea);
    }

    /**
     * Get idea
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdea()
    {
        return $this->idea;
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
