<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementRucheSimple
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\ElementRucheSimpleRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="nom", type="string")
 * @ORM\DiscriminatorMap({"cadre" = "Cadre"}) 
 */
abstract class ElementRucheSimple extends ElementRuche
{
    /**
     * @var ElementRucheCompose
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\ElementRucheCompose")
     */
    private $elementRucheCompose;
    
    
    /**
     * Set elementRucheCompose
     *
     * @param ElementRucheCompose $elementRucheCompose
     * @return ElementRucheSimple
     */
    public function setElementRucheCompose(ElementRucheCompose $elementRucheCompose)
    {
        $this->elementRucheCompose = $elementRucheCompose;

        return $this;
    }
    
    /**
     * Get ElementRucheCompose
     *
     * @return ElementRucheCompose 
     */
    public function getElementRucheCompose()
    {
        return $this->elementRucheCompose;
    }    
}
