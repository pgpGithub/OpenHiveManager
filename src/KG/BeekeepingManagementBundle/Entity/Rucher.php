<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rucher
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\RucherRepository")
 */
class Rucher
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
      * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Exploitation", inversedBy="ruchers")
      * @ORM\JoinColumn(nullable=false)
      */
    private $exploitation;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez remplir le nom du rucher")
     * @Assert\Length(max=25, maxMessage="Le nom du rucher ne peut dépasser {{ limit }} caractères")
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", mappedBy="rucher")
     * @Assert\Valid()
     */
    private $emplacements;    
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Image", cascade={"persist"})
     * @Assert\Valid()
     */
    private $image;
  
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Localisation", cascade={"persist"})
     * @Assert\Valid()
     */
    private $localisation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="supprime", type="boolean")
     */
    private $supprime = false;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emplacements = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set nom
     *
     * @param string $nom
     * @return Rucher
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Rucher
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set image
     *
     * @param Image $image
     * @return Rucher
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set localisation
     *
     * @param Localisation $localisation
     * @return Rucher
     */
    public function setLocalisation(Localisation $localisation)
    {
        $this->localisation= $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return Localisation 
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }
    
    /**
     * Set exploitation
     *
     * @param Exploitation $exploitation
     * @return Rucher
     */
    public function setExploitation(Exploitation $exploitation)
    {
        $this->exploitation= $exploitation;

        return $this;
    }

    /**
     * Get Exploitation
     *
     * @return Exploitation 
     */
    public function getExploitation()
    {
        return $this->exploitation;
    }
    
    /**
     * Set supprime
     *
     * @param string $supprime
     * @return Rucher
     */
    public function setSupprime($supprime)
    {
        $this->supprime = $supprime;

        return $this;
    }

    /**
     * Get supprime
     *
     * @return boolean 
     */
    public function getSupprime()
    {
        return $this->supprime;
    }      

    /**
     * Add emplacements
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacements
     * @return Rucher
     */
    public function addEmplacement(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacements)
    {
        $this->emplacements[] = $emplacements;

        return $this;
    }

    /**
     * Remove emplacements
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacements
     */
    public function removeEmplacement(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacements)
    {
        $this->emplacements->removeElement($emplacements);
    }

    /**
     * Get emplacements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmplacements()
    {
        return $this->emplacements;
    }
}
