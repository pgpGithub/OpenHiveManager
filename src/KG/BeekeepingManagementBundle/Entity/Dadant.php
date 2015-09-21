<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Dadant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\DadantRepository")
 */
class Dadant extends Ruche
{

    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Corps", mappedBy="ruche", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $corps;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Hausse", mappedBy="ruche", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $hausses; 

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Matiere")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner la matière de la ruche")
     */
    private $matiere;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hausses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->corps   = new Corps();
    }

    /**
     * Set corps
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Corps $corps
     * @return Dadant
     */
    public function setCorps(\KG\BeekeepingManagementBundle\Entity\Corps $corps)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Corps 
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Add hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Hausse $hausses
     * @return Dadant
     */
    public function addHauss(\KG\BeekeepingManagementBundle\Entity\Hausse $hausses)
    {
        $this->hausses[] = $hausses;

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
     * Set matiere
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Matiere $matiere
     * @return Dadant
     */
    public function setMatiere(\KG\BeekeepingManagementBundle\Entity\Matiere $matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Matiere 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
}
