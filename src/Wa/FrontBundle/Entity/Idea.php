<?php

namespace Wa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
}
