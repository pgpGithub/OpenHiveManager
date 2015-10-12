<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * HausseVisite
 * 
 * @ORM\Table() 
 * @ORM\Entity
 */
class HausseVisite extends Hausse
{
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", inversedBy="hausses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $visite;

    /**
     * Constructor
     */
    public function __construct(Visite $visite, $nbplein = 0)
    {
        $ruche = $visite->getColonie()->getRuche();
        $this->visite = $visite;   
        
        
        if($nbplein){
            parent::setNbplein($nbplein);
        }
    }

    /**
     * Set visite
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $visite
     * @return Hausse
     */
    public function setVisite(\KG\BeekeepingManagementBundle\Entity\Ruche $visite)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Ruche 
     */
    public function getVisite()
    {
        return $this->visite;
    } 
}
