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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="date")
     * @Assert\Date()
     */
    private $dateCreation;

    /**
    * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", mappedBy="rucher")
    */
    private $ruches;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
  
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Location", cascade={"persist"})
     */
    private $location;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ruches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add ruche
     *
     * @param Ruche $ruche
     * @return Rucher
     */
    public function addRuche(Ruche $ruche)
    {
        $this->ruches[] = $ruche;
        
        $ruche->setRucher($this);

        return $this;
    }

    /**
     * Remove ruche
     *
     * @param Ruche $ruches
     */
    public function removeRuche(Ruche $ruche)
    {
        $this->ruches->removeElement($ruche);
    }

    /**
     * Get ruches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRuches()
    {
        return $this->ruches;
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
     * Set location
     *
     * @param Location $location
     * @return Rucher
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return Location 
     */
    public function getLocation()
    {
        return $this->location;
    }
}
