<?php

namespace Wa\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Wa\FrontBundle\Entity\Idea;

/**
 * Upload
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\MemberBundle\Repository\UploadRepository")
 */
class Upload
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
    
    private $file;
    
    /**
     *
     * @var type Idea
     * 
     * @ORM\ManyToOne(targetEntity="Wa\FrontBundle\Entity\Idea", inversedBy="uploads")
     */
    private $idea;


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
     * Set name
     *
     * @param string $name
     * @return Upload
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Upload
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set idea
     *
     * @param \Wa\FrontBundle\Entity\Idea $idea
     * @return Upload
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
}
