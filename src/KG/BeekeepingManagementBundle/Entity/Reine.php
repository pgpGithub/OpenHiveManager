<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Reine
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @Assert\NotBlank(message="Veuillez sélectionner la race")
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
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Remerage", mappedBy="reine", cascade="persist")
     * @Assert\Valid()
     */
    private $remerage;

    /**
     * Constructor
     */
    public function __construct( \DateTime $date = null, Race $race = null)
    {          
        $this->race       = $race;
        $this->anneeReine = $date;
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

    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {              
        $today = new \DateTime();
        
        if( $this->anneeReine > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('anneeReine')
                   ->addViolation();            
        }
       
    }        

    /**
     * Set remerage
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Remerage $remerage
     * @return Reine
     */
    public function setRemerage(\KG\BeekeepingManagementBundle\Entity\Remerage $remerage = null)
    {
        $this->remerage = $remerage;

        return $this;
    }

    /**
     * Get remerage
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Remerage 
     */
    public function getRemerage()
    {
        return $this->remerage;
    }
}
