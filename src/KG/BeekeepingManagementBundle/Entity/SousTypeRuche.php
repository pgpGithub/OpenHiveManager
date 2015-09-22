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
     * @ORM\Column(name="nbcadres", type="integer")
     */
    private $nbcadres;

    /**
     * @ORM\ManyToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\TypeRuche", inversedBy="soustypes")
     */
    private $types;
    
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
     * Set nbCadres
     *
     * @param integer $nbCadres
     * @return SousTypeRuche
     */
    public function setNbCadres($nbCadres)
    {
        $this->nbCadres = $nbCadres;

        return $this;
    }

    /**
     * Get nbCadres
     *
     * @return integer 
     */
    public function getNbCadres()
    {
        return $this->nbCadres;
    }
}
