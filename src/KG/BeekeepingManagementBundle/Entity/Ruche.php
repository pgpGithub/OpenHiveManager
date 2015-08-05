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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var Rucher
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher", inversedBy="ruches")
     * @ORM\JoinColumn()
     */
    private $rucher;

    /**
     * @var Exploitation
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Exploitation", inversedBy="ruches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Image", cascade={"persist"})
     * @Assert\Valid()
     */
    private $image;

     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonnie", inversedBy="Ruche")
     * @Assert\Valid()
     */
    private $Colonnie;
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", mappedBy="ruche")
     * @Assert\Valid()
     */
    private $visites;
    
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
     * Set rucher
     *
     * @param Rucher $rucher
     * @return Ruche
     */
    public function setRucher(Rucher $rucher)
    {
        $this->rucher = $rucher;

        return $this;
    }
    
    /**
     * Get rucher
     *
     * @return Rucher 
     */
    public function getRucher()
    {
        return $this->rucher;
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
     * Constructor
     */
    public function __construct()
    {
        $this->visites = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add visites
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Visite $visites
     * @return Ruche
     */
    public function addVisite(\KG\BeekeepingManagementBundle\Entity\Visite $visites)
    {
        $this->visites[] = $visites;

        return $this;
    }

    /**
     * Remove visites
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Visite $visites
     */
    public function removeVisite(\KG\BeekeepingManagementBundle\Entity\Visite $visites)
    {
        $this->visites->removeElement($visites);
    }

    /**
     * Get visites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVisites()
    {
        return $this->visites;
    }

    /**
     * Set Colonnie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie
     * @return Ruche
     */
    public function setColonnie(\KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie = null)
    {
        $this->Colonnie = $colonnie;

        return $this;
    }

    /**
     * Get Colonnie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonnie 
     */
    public function getColonnie()
    {
        return $this->Colonnie;
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
}
