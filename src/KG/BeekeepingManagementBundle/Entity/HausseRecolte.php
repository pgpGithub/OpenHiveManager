<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * HausseRecolte
 * 
 * @ORM\Table() 
 * @ORM\Entity
 */
class HausseRecolte extends Hausse
{    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\RecolteRuche", inversedBy="hausses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recolteruche;

    /**
     * Constructor
     */
    public function __construct(HausseRuche $hausse, RecolteRuche $recolte)
    {
        parent::setNbcadres($hausse->getNbcadres());
        parent::setNbplein($hausse->getNbplein());
        parent::setType($hausse->getType());
        $this->recolteruche = $recolte;
    }
    
    /**
     * Set recolteruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\RecolteRuche $recolteruche
     * @return Hausse
     */
    public function setRecolteruche(\KG\BeekeepingManagementBundle\Entity\RecolteRuche $recolteruche)
    {
        $this->recolteruche = $recolteruche;

        return $this;
    }

    /**
     * Get recolteruche
     *
     * @return \KG\BeekeepingManagementBundle\Entity\RecolteRuche 
     */
    public function getRecolteruche()
    {
        return $this->recolteruche;
    }  
}
