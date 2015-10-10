<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Transhumance
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\TranshumanceRepository")
 */
class Transhumance
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
     * @var Colonie
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="transhumances", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $colonie;    

    /**
     * @var Emplacement
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", inversedBy="transhumancesfrom")
     * @ORM\JoinColumn(nullable=false)
     */
    private $emplacementfrom;  

    /**
     * @var Emplacement
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", inversedBy="transhumancesto")
     * @ORM\JoinColumn(nullable=false)
     */
    private $emplacementto;      
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
    
    /**
     * Constructor
     */
    public function __construct(Colonie $colonie)
    {
        $this->colonie = $colonie;
        $this->emplacementfrom = $colonie->getRuche()->getEmplacement();
    }
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        
        foreach( $this->getColonie()->getTranshumances() as $lastTranshumance ){
            if ( $this->date < $lastTranshumance->getDate()  && $lastTranshumance->getId() != $this->getId() ){                
                $context
                       ->buildViolation('La date ne peut pas être antérieur à celle d\'une ancienne transhumance') 
                       ->atPath('date')
                       ->addViolation();
            }            
        }
        
        if( $this->date < $this->getColonie()->getDateColonie() ){
            $context
                   ->buildViolation('La date ne peut pas être antérieur à celle de la naissance de la colonie') 
                   ->atPath('date')
                   ->addViolation();            
        }
        
        $today = new \DateTime();
        
        if( $this->date > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('date')
                   ->addViolation();            
        }
    
        if( $this->emplacementfrom->getRucher() == $this->emplacementto->getRucher() ){
            $context
                   ->buildViolation('La transhumance ne peut pas se faire dans le même rucher') 
                   ->atPath('rucherto')
                   ->addViolation();            
        }
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
     * Set date
     *
     * @param \DateTime $date
     * @return Transhumance
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Transhumance
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie)
    {
        $this->colonie = $colonie;

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
     * Set emplacementfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementfrom
     * @return Transhumance
     */
    public function setEmplacementfrom(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementfrom)
    {
        $this->emplacementfrom = $emplacementfrom;

        return $this;
    }

    /**
     * Get emplacementfrom
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Emplacement 
     */
    public function getEmplacementfrom()
    {
        return $this->emplacementfrom;
    }

    /**
     * Set emplacementto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementto
     * @return Transhumance
     */
    public function setEmplacementto(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementto)
    {
        $this->emplacementto = $emplacementto;

        return $this;
    }

    /**
     * Get emplacementto
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Emplacement 
     */
    public function getEmplacementto()
    {
        return $this->emplacementto;
    }
}
