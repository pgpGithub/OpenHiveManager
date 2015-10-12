<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * HausseRuche
 * 
 * @ORM\Table() 
 * @ORM\Entity
 */
class HausseRuche extends Hausse
{
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", inversedBy="hausses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruche;

    /**
     * Constructor
     */
    public function __construct(HausseVisite $hausse)
    {
        parent::setNbcadres($hausse->getNbcadres());
        parent::setNbplein($hausse->getNbplein());
        $this->ruche = $hausse->getVisite()->getColonie()->getRuche();
    }

    /**
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Hausse
     */
    public function setRuche(\KG\BeekeepingManagementBundle\Entity\Ruche $ruche)
    {
        $this->ruche = $ruche;
        return $this;
    }

    /**
     * Get ruche
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Ruche 
     */
    public function getRuche()
    {
        return $this->ruche;
    }   
}
