<?php

namespace Wa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArtWork
 *
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
}
