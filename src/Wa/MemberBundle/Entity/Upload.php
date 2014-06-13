<?php

namespace Wa\MemberBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use \Wa\FrontBundle\Entity\Idea;
use JMS\Serializer\Annotation as JMS;

/**
 * Upload
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wa\MemberBundle\Repository\UploadRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Upload {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
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
     * @JMS\Expose
     */
    private $path;
    private $file;
    private $tempFilePath;

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
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Upload
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Upload
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set idea
     *
     * @param \Wa\FrontBundle\Entity\Idea $idea
     * @return Upload
     */
    public function setIdea(\Wa\FrontBundle\Entity\Idea $idea = null) {
        $this->idea = $idea;

        return $this;
    }

    /**
     * Get idea
     *
     * @return \Wa\FrontBundle\Entity\Idea 
     */
    public function getIdea() {
        return $this->idea;
    }
    
    public function getFile() {
        return $this->file;
    }
    
    public function setFile(UploadedFile $file) {
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->path) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilePath = $this->path;

            // On réinitialise les valeurs des attributs url et alt
            $this->path = null;
            $this->name = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }

        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        $this->path = $this->file->guessExtension();

        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->name = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilePath) {
            $oldFile = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->tempFilePath;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        $this->file->move($this->getUploadRootDir(), $this->id . '.' . $this->path);
        unset($this->file);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload() {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilePath = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->path;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilePath)) {
            // On supprime le fichier
            unlink($this->tempFilePath);
        }
    }

    public function getUploadDir() {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/images';
    }

    protected function getUploadRootDir() {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

}
