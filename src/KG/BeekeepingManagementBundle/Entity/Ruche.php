<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ruche
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\RucheRepository")
 */
class Ruche
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\TypeRuche")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner le type de la ruche")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Matiere")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner la matière de la ruche")
     */
    private $matiere;
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Exploitation", inversedBy="ruches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Cadre", mappedBy="ruche", cascade={"persist"})
     * @Assert\Valid() 
     */
    private $cadres;  

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Hausse", mappedBy="ruche", cascade={"persist", "remove"}, orphanRemoval=true))
     * @Assert\Valid() 
     */
    private $hausses; 
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Image", cascade={"persist"})
     * @Assert\Valid()
     */
    private $image;

     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonnie", inversedBy="ruche")
     * @Assert\Valid()
     */
    private $colonnie;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", inversedBy="ruche")
     * @Assert\Valid()
     */
    private $emplacement;

    /**
     * @var boolean
     * @ORM\Column(name="supprime", type="boolean")
     */
    private $supprime = false;
    
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
     * @return Ruche
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
     * Set type
     *
     * @param string $type
     * @return Ruche
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set image
     *
     * @param Image $image
     * @return Ruche
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
     * Set Colonnie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie
     * @return Ruche
     */
    public function setColonnie(\KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie = null)
    {
        $this->colonnie = $colonnie;

        return $this;
    }

    /**
     * Get Colonnie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonnie 
     */
    public function getColonnie()
    {
        return $this->colonnie;
    }

    /**
     * Set exploitation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Exploitation $exploitation
     * @return Ruche
     */
    public function setExploitation(\KG\BeekeepingManagementBundle\Entity\Exploitation $exploitation)
    {
        $this->exploitation = $exploitation;

        return $this;
    }

    /**
     * Get exploitation
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Exploitation 
     */
    public function getExploitation()
    {
        return $this->exploitation;
    }
    
    /**
     * Set supprime
     *
     * @param string $supprime
     * @return Ruche
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
     * Set emplacement
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacement
     * @return Ruche
     */
    public function setEmplacement(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacement = null)
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    /**
     * Get emplacement
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Emplacement 
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }

    /**
     * Set matiere
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Matiere $matiere
     * @return Ruche
     */
    public function setMatiere(\KG\BeekeepingManagementBundle\Entity\Matiere $matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Matiere 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cadres  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hausses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cadres
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Cadre $cadres
     * @return Ruche
     */
    public function addCadre(\KG\BeekeepingManagementBundle\Entity\Cadre $cadres)
    {
        $this->cadres[] = $cadres;

        return $this;
    }

    /**
     * Remove cadres
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Cadre $cadres
     */
    public function removeCadre(\KG\BeekeepingManagementBundle\Entity\Cadre $cadres)
    {
        $this->cadres->removeElement($cadres);
    }

    /**
     * Get cadres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCadres()
    {
        return $this->cadres;
    }

    /**
     * Add hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Hausse $hausses
     * @return Ruche
     */
    public function addHauss(\KG\BeekeepingManagementBundle\Entity\Hausse $hausses)
    {
        $this->hausses[] = $hausses;

        return $this;
    }

    /**
     * Remove hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Hausse $hausses
     */
    public function removeHauss(\KG\BeekeepingManagementBundle\Entity\Hausse $hausses)
    {
        $this->hausses->removeElement($hausses);
    }

    /**
     * Get hausses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHausses()
    {
        return $this->hausses;
    }
}
