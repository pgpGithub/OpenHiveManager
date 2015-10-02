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
     * @var Rucher
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rucherfrom;  

    /**
     * @var Rucher
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rucherto;      
    
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
        $this->rucherfrom = $colonie->getRucher();
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
    
        if( $this->rucherfrom == $this->rucherto ){
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
     * Set rucherfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucherfrom
     * @return Transhumance
     */
    public function setRucherfrom(\KG\BeekeepingManagementBundle\Entity\Rucher $rucherfrom)
    {
        $this->rucherfrom = $rucherfrom;

        return $this;
    }

    /**
     * Get rucherfrom
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Rucher 
     */
    public function getRucherfrom()
    {
        return $this->rucherfrom;
    }

    /**
     * Set rucherto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucherto
     * @return Transhumance
     */
    public function setRucherto(\KG\BeekeepingManagementBundle\Entity\Rucher $rucherto)
    {
        $this->rucherto = $rucherto;

        return $this;
    }

    /**
     * Get rucherto
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Rucher 
     */
    public function getRucherto()
    {
        return $this->rucherto;
    }
}
