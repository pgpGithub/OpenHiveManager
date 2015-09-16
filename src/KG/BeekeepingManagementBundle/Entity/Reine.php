<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reine
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\ReineRepository")
 */
class Reine
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
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Race")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Assert\NotBlank(message="Veuillez sélectionner la race de la colonnie")
     */
    private $race;
   
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="anneeReine", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $anneeReine;

    /**
     * @var boolean
     *
     * @ORM\Column(name="clippage", type="boolean", nullable=true)
     */
    private $clippage;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="marquage", type="boolean", nullable=true)
     */
    private $marquage;    

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\ProvenanceReine")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $provenanceReine;
        
     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonnie", mappedBy="reine", cascade="persist")
     * @Assert\Valid()
     */
    private $colonnie;
    
    /**
     * Constructor
     */
    public function __construct()
    {

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
     * Set anneeReine
     *
     * @param \DateTime $anneeReine
     * @return Reine
     */
    public function setAnneeReine($anneeReine)
    {
        $this->anneeReine = $anneeReine;

        return $this;
    }

    /**
     * Get anneeReine
     *
     * @return \DateTime 
     */
    public function getAnneeReine()
    {
        return $this->anneeReine;
    }

    /**
     * Set clippage
     *
     * @param boolean $clippage
     * @return Reine
     */
    public function setClippage($clippage)
    {
        $this->clippage = $clippage;

        return $this;
    }

    /**
     * Get clippage
     *
     * @return boolean 
     */
    public function getClippage()
    {
        return $this->clippage;
    }

    /**
     * Set exploitation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Exploitation $exploitation
     * @return Reine
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
     * Set race
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Race $race
     * @return Reine
     */
    public function setRace(\KG\BeekeepingManagementBundle\Entity\Race $race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Race 
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set provenanceReine
     *
     * @param \KG\BeekeepingManagementBundle\Entity\ProvenanceReine $provenanceReine
     * @return Reine
     */
    public function setProvenanceReine(\KG\BeekeepingManagementBundle\Entity\ProvenanceReine $provenanceReine = null)
    {
        $this->provenanceReine = $provenanceReine;

        return $this;
    }

    /**
     * Get provenanceReine
     *
     * @return \KG\BeekeepingManagementBundle\Entity\ProvenanceReine 
     */
    public function getProvenanceReine()
    {
        return $this->provenanceReine;
    }

    /**
     * Set colonnie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie
     * @return Reine
     */
    public function setColonnie(\KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie = null)
    {
        $this->colonnie = $colonnie;

        return $this;
    }

    /**
     * Get colonnie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonnie 
     */
    public function getColonnie()
    {
        return $this->colonnie;
    }

    /**
     * Set marquage
     *
     * @param boolean $marquage
     * @return Reine
     */
    public function setMarquage($marquage)
    {
        $this->marquage = $marquage;

        return $this;
    }

    /**
     * Get marquage
     *
     * @return boolean 
     */
    public function getMarquage()
    {
        return $this->marquage;
    }
    
}
