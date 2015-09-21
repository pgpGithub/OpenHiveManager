<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cadre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\HausseRepository")
 */
class Hausse
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
     * @ORM\Column(name="nbplein", type="integer")
     */
    private $nbplein = 0;    

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", inversedBy="hausses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruche;
    
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
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Hausse
     */
    public function setRuche(\KG\BeekeepingManagementBundle\Entity\Ruche $ruche = NULL)
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

    /**
     * Set nbplein
     *
     * @param integer $nbplein
     * @return Hausse
     */
    public function setNbplein($nbplein)
    {
        $this->nbplein = $nbplein;

        return $this;
    }

    /**
     * Get nbplein
     *
     * @return integer 
     */
    public function getNbplein()
    {
        return $this->nbplein;
    }
}
