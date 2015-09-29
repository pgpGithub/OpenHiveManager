<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * RecolteRuche
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RecolteRuche
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
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="recoltesruche")
     * @ORM\JoinColumn(nullable=false)
     */
    private $colonie;    

    /**
     * @var Recolterucher
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\RecolteRucher", inversedBy="recoltesruche")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recolterucher;  
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Hausse", mappedBy="recolteruche", cascade="persist")
     * @Assert\Valid()
     */
    private $hausses; 
    
    /**
     * Constructor
     */
    public function __construct(Ruche $ruche, \KG\BeekeepingManagementBundle\Entity\RecolteRucher $recolterucher)
    {
        $this->hausses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->colonie = $ruche->getColonie();
        $this->recolterucher = $recolterucher;
        
        foreach( $ruche->getHausses() as $hausse ){
            if( $hausse->getNbplein() > 0 ){
                $this->addHauss($hausse);
            }
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
     * Set colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return RecolteRuche
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
     * Add hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Hausse $hausses
     * @return RecolteRuche
     */
    public function addHauss(\KG\BeekeepingManagementBundle\Entity\Hausse $hausse)
    {
        $this->hausses[] = $hausse;
        $hausse->setRuche();
        $hausse->setRecolteruche($this);
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
     * Set recolterucher
     *
     * @param \KG\BeekeepingManagementBundle\Entity\RecolteRucher $recolterucher
     * @return RecolteRuche
     */
    public function setRecolterucher(\KG\BeekeepingManagementBundle\Entity\RecolteRucher $recolterucher)
    {
        $this->recolterucher = $recolterucher;

        return $this;
    }

    /**
     * Get recolterucher
     *
     * @return \KG\BeekeepingManagementBundle\Entity\RecolteRucher 
     */
    public function getRecolterucher()
    {
        return $this->recolterucher;
    }
}
