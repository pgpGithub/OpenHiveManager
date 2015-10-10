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
    public function __construct(Visite $visite, $nbplein = null)
    {
        $ruche = $visite->getColonie()->getRuche();
        $this->visite = $visite;   
        parent::setType( $ruche->getCorps()->getType() );        
        
        if( $ruche->getCorps()->getType()->getLibelle() == 'Langstroth' ){
            parent::setNbcadres($ruche->getCorps()->getSoustype()->getNbcadres()); 
        }
        else{
            parent::setNbcadres($ruche->getCorps()->getSoustype()->getNbcadres() - 1);            
        }
        
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
