<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ruche
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Image", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $image;

     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="ruche", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $colonie;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", inversedBy="ruche")
     * @Assert\Valid()
     */
    private $emplacement;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Corps", mappedBy="ruche", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $corps;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Hausse", mappedBy="ruche", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $hausses; 
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Matiere")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner la matière de la ruche")
     */
    private $matiere;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hausses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->corps   = new Corps();
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
     * Set Colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Ruche
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie = null)
    {
        $this->colonie = $colonie;

        return $this;
    }

    /**
     * Get Colonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonie()
    {
        return $this->colonie;
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
     * Set corps
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Corps $corps
     * @return Ruche
     */
    public function setCorps(\KG\BeekeepingManagementBundle\Entity\Corps $corps)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Corps 
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Add hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Hausse $hausse
     * @return Ruche
     */
    public function addHauss(\KG\BeekeepingManagementBundle\Entity\Hausse $hausse)
    {       
        // Si c'est une Langstroth
        if( $this->getType()->getId() == 1 ){
            $nbcadres = $this->getCorps()->getNbmaxcadres(); 
        }
        else{
            $nbcadres = $this->getCorps()->getNbmaxcadres() - 1;            
        }
        
        $new_hausse = $hausse;
        $new_hausse->setNbcadres($nbcadres);
        $this->hausses[] = $new_hausse;

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

}
