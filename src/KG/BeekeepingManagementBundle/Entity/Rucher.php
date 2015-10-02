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
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", mappedBy="rucher", cascade={"remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $emplacements;    
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Image", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $image;
  
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Proprietaire", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $proprietaire;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Localisation", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $localisation;
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", mappedBy="rucher")
     * @Assert\Valid()
     */
    private $colonies; 

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\RecolteRucher", mappedBy="rucher", cascade={"remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $recoltesrucher;
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Transhumance", mappedBy="rucherfrom", cascade={"remove"}, orphanRemoval=true)
     */
    private $transhumancesfrom;
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Transhumance", mappedBy="rucherto", cascade={"remove"}, orphanRemoval=true)
     */
    private $transhumancesto;
    
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

    /**
     * Set proprietaire
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Proprietaire $proprietaire
     * @return Rucher
     */
    public function setProprietaire(\KG\BeekeepingManagementBundle\Entity\Proprietaire $proprietaire = null)
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Proprietaire 
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }

    /**
     * Add colonies
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonies
     * @return Rucher
     */
    public function addColony(\KG\BeekeepingManagementBundle\Entity\Colonie $colonies)
    {
        $this->colonies[] = $colonies;

        return $this;
    }

    /**
     * Remove colonies
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonies
     */
    public function removeColony(\KG\BeekeepingManagementBundle\Entity\Colonie $colonies)
    {
        $this->colonies->removeElement($colonies);
    }

    /**
     * Get colonies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColonies()
    {
        return $this->colonies;
    }

    /**
     * Add recoltesrucher
     *
     * @param \KG\BeekeepingManagementBundle\Entity\RecolteRucher $recoltesrucher
     * @return Rucher
     */
    public function addRecoltesrucher(\KG\BeekeepingManagementBundle\Entity\RecolteRucher $recoltesrucher)
    {
        $this->recoltesrucher[] = $recoltesrucher;

        return $this;
    }

    /**
     * Remove recoltesrucher
     *
     * @param \KG\BeekeepingManagementBundle\Entity\RecolteRucher $recoltesrucher
     */
    public function removeRecoltesrucher(\KG\BeekeepingManagementBundle\Entity\RecolteRucher $recoltesrucher)
    {
        $this->recoltesrucher->removeElement($recoltesrucher);
    }

    /**
     * Get recoltesrucher
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecoltesrucher()
    {
        return $this->recoltesrucher;
    }

    /**
     * Add transhumancesfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom
     * @return Rucher
     */
    public function addTranshumancesfrom(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom)
    {
        $this->transhumancesfrom[] = $transhumancesfrom;

        return $this;
    }

    /**
     * Remove transhumancesfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom
     */
    public function removeTranshumancesfrom(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom)
    {
        $this->transhumancesfrom->removeElement($transhumancesfrom);
    }

    /**
     * Get transhumancesfrom
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranshumancesfrom()
    {
        return $this->transhumancesfrom;
    }

    /**
     * Add transhumancesto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto
     * @return Rucher
     */
    public function addTranshumancesto(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto)
    {
        $this->transhumancesto[] = $transhumancesto;

        return $this;
    }

    /**
     * Remove transhumancesto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto
     */
    public function removeTranshumancesto(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto)
    {
        $this->transhumancesto->removeElement($transhumancesto);
    }

    /**
     * Get transhumancesto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranshumancesto()
    {
        return $this->transhumancesto;
    }
}
