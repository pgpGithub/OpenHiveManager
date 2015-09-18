<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousTypeRuche
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\SousTypeRucheRepository")
 */
class SousTypeRuche
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
     * @var integer
     *
     * @ORM\Column(name="nbcadre", type="integer")
     */
    private $nbcadre;


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
     * Set nbcadre
     *
     * @param integer $nbcadre
     * @return SousTypeRuche
     */
    public function setNbcadre($nbcadre)
    {
        $this->nbcadre = $nbcadre;

        return $this;
    }

    /**
     * Get nbcadre
     *
     * @return integer 
     */
    public function getNbcadre()
    {
        return $this->nbcadre;
    }
}
