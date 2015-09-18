<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeRuche
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\TypeRucheRepository")
 */
class TypeRuche
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=20)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\SousTypeRuche")
     */
    private $soustypes;
    
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
     * Set libelle
     *
     * @param string $libelle
     * @return TypeRuche
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->soustypes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add soustypes
     *
     * @param \OC\PlatformBundle\Entity\SousTypeRuche $soustypes
     * @return TypeRuche
     */
    public function addSoustype(\OC\PlatformBundle\Entity\SousTypeRuche $soustypes)
    {
        $this->soustypes[] = $soustypes;

        return $this;
    }

    /**
     * Remove soustypes
     *
     * @param \OC\PlatformBundle\Entity\SousTypeRuche $soustypes
     */
    public function removeSoustype(\OC\PlatformBundle\Entity\SousTypeRuche $soustypes)
    {
        $this->soustypes->removeElement($soustypes);
    }

    /**
     * Get soustypes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoustypes()
    {
        return $this->soustypes;
    }
}
