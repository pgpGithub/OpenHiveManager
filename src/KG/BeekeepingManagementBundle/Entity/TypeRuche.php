<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * TypeRuche
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields="libelle", message="Un type de ruche existe déjà avec ce libellé")
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
     * @ORM\Column(name="libelle", type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Veuillez remplir le libellé du type de ruche")
     * @Assert\Length(max=15, maxMessage="Le libellé du type de ruche ne peut dépasser {{ limit }} caractères")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\SousTypeRuche", mappedBy="types")
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
     * @param \KG\BeekeepingManagementBundle\Entity\SousTypeRuche $soustypes
     * @return TypeRuche
     */
    public function addSoustype(\KG\BeekeepingManagementBundle\Entity\SousTypeRuche $soustypes)
    {
        $this->soustypes[] = $soustypes;

        return $this;
    }

    /**
     * Remove soustypes
     *
     * @param \KG\BeekeepingManagementBundle\Entity\SousTypeRuche $soustypes
     */
    public function removeSoustype(\KG\BeekeepingManagementBundle\Entity\SousTypeRuche $soustypes)
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
