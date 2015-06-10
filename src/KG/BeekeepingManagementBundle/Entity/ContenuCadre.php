<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContenuCadre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\ContenuCadreRepository")
 */
class ContenuCadre
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
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;
    
    /**
     * @var Cadre
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Cadre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cadre;

    /**
     * @var Contenu
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Contenu")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contenu;
    
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
     * Set quantite
     *
     * @param $quantite
     * @return ContenuCadre
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
    
    /**
     * Set cadre
     *
     * @param Cadre $cadre
     * @return ContenuCadre
     */
    public function setCadre(Cadre $cadre)
    {
        $this->cadre = $cadre;

        return $this;
    }

    /**
     * Get cadre
     *
     * @return Cadre
     */
    public function getCadre()
    {
        return $this->cadre;
    }   
    
    /**
     * Set contenu
     *
     * @param Contenu $contenu
     * @return ContenuCadre
     */
    public function setContenu(Contenu $contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return Contenu
     */
    public function getContenu()
    {
        return $this->contenu;
    }        
}
