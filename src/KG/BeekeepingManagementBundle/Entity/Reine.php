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
     * @Assert\NotBlank(message="Veuillez sélectionner la race de la colonie")
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
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="reines", cascade="persist")
     * @Assert\Valid()
     */
    private $colonie;

    /**
     * Constructor
     */
    public function __construct(Colonie $colonie = null, \DateTime $date = null, Race $race = null)
    {          
        $this->race       = $race;
        $this->anneeReine = $date;
        $this->setColonie($colonie); 
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
     * Set 
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Reine
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie = null)
    {
        $this->colonie = $colonie;
        
        if($colonie){
            $this->colonie->addReine($this);
        }
        
        return $this;
    }

    /**
     * Get colonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonie()
    {
        return $this->colonie;
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
        foreach( $this->getColonie()->getReines() as $lastReine ){
            if ( $this->anneeReine < $lastReine->getAnneeReine()  && $lastReine->getId() != $this->getId() ){                
                $context
                       ->buildViolation('L\'année de la reine ne peut pas être antérieur à celle d\'une ancienne reine') 
                       ->atPath('anneeReine')
                       ->addViolation();
            }            
        }
        
        $today = new \DateTime();
        
        if( $this->anneeReine > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('anneeReine')
                   ->addViolation();            
        }
    }        
}
