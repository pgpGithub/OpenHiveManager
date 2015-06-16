<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementRucheCompose
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\ElementRucheComposeRepository")
 */
abstract class ElementRucheCompose extends ElementRuche
{
    /**
     * @var string
     *
     * @ORM\Column(name="nbContenuMax", type="integer")
     */
    private $nbContenuMax;
    
    
    /**
     * Set nbContenuMax
     *
     * @param integer $nbContenuMax
     * @return ElementRucheCompose
     */
    public function setNbContenuMax($nbContenuMax)
    {
        $this->nbContenuMax = $nbContenuMax;

        return $this;
    }

    /**
     * Get nbContenuMax
     *
     * @return integer 
     */
    public function getNbContenuMax()
    {
        return $this->nbContenuMax;
    }
}
