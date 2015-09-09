<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Visite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\VisiteRepository")
 */
class Visite
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
     * @var Ruche
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonnie", inversedBy="visites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $colonnie;    
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Activite")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activite;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="reine", type="boolean")
     */
    private $reine = false;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="essaimage", type="boolean")
     */
    private $essaimage = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Etat")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'état de la colonnie")
     */
    private $etat;
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Agressivite")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'agressivité de la colonnie")
     */
    private $agressivite;

    /**
     * @var string
     *
     * @ORM\Column(name="nourrissement", type="string", length=50)  
     * @Assert\Length(max=50, maxMessage="Le type de nourrissement ne peut dépasser {{ limit }} caractères")
     */
    private $nourrissement;

    /**
     * @var string
     *
     * @ORM\Column(name="traitement", type="string", length=50)  
     * @Assert\Length(max=50, maxMessage="Le type de traitement ne peut dépasser {{ limit }} caractères")
     */
    private $traitement;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="miel", type="integer")
     */
    private $miel;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="pollen", type="integer")
     */
    private $pollen;

    /**
     * @var integer
     *
     * @ORM\Column(name="propolis", type="integer")
     */
    private $propolis;    

    /**
     * @var integer
     *
     * @ORM\Column(name="gelee", type="integer")
     */
    private $gelee; 

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text", length=300)
     * @Assert\Length(max=300, maxMessage="Le champ observations ne peut dépasser {{ limit }} caractères") 
     */
    private $observations;
    
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
     * Set activite
     *
     * @param Activite $activite
     * @return Visite
     */
    public function setActivite(Activite $activite)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set colonnie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie
     * @return Visite
     */
    public function setColonnie(\KG\BeekeepingManagementBundle\Entity\Colonnie $colonnie)
    {
        $this->colonnie = $colonnie;

        return $this;
    }

    /**
     * Get colonnie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonnie 
     */
    public function getColonnie()
    {
        return $this->colonnie;
    }   

    /**
     * Set reine
     *
     * @param boolean $reine
     * @return Visite
     */
    public function setReine($reine)
    {
        $this->reine = $reine;

        return $this;
    }

    /**
     * Get reine
     *
     * @return boolean 
     */
    public function getReine()
    {
        return $this->reine;
    }

    /**
     * Set essaimage
     *
     * @param boolean $essaimage
     * @return Visite
     */
    public function setEssaimage($essaimage)
    {
        $this->essaimage = $essaimage;

        return $this;
    }

    /**
     * Get essaimage
     *
     * @return boolean 
     */
    public function getEssaimage()
    {
        return $this->essaimage;
    }

    /**
     * Set nourrissement
     *
     * @param string $nourrissement
     * @return Visite
     */
    public function setNourrissement($nourrissement)
    {
        $this->nourrissement = $nourrissement;

        return $this;
    }

    /**
     * Get nourrissement
     *
     * @return string 
     */
    public function getNourrissement()
    {
        return $this->nourrissement;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return Visite
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
     * Set traitement
     *
     * @param string $traitement
     * @return Visite
     */
    public function setTraitement($traitement)
    {
        $this->traitement = $traitement;

        return $this;
    }

    /**
     * Get traitement
     *
     * @return string 
     */
    public function getTraitement()
    {
        return $this->traitement;
    }

    /**
     * Set miel
     *
     * @param integer $miel
     * @return Visite
     */
    public function setMiel($miel)
    {
        $this->miel = $miel;

        return $this;
    }

    /**
     * Get miel
     *
     * @return integer 
     */
    public function getMiel()
    {
        return $this->miel;
    }

    /**
     * Set pollen
     *
     * @param integer $pollen
     * @return Visite
     */
    public function setPollen($pollen)
    {
        $this->pollen = $pollen;

        return $this;
    }

    /**
     * Get pollen
     *
     * @return integer 
     */
    public function getPollen()
    {
        return $this->pollen;
    }

    /**
     * Set propolis
     *
     * @param integer $propolis
     * @return Visite
     */
    public function setPropolis($propolis)
    {
        $this->propolis = $propolis;

        return $this;
    }

    /**
     * Get propolis
     *
     * @return integer 
     */
    public function getPropolis()
    {
        return $this->propolis;
    }

    /**
     * Set gelee
     *
     * @param integer $gelee
     * @return Visite
     */
    public function setGelee($gelee)
    {
        $this->gelee = $gelee;

        return $this;
    }

    /**
     * Get gelee
     *
     * @return integer 
     */
    public function getGelee()
    {
        return $this->gelee;
    }

    /**
     * Set observations
     *
     * @param string $observations
     * @return Visite
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string 
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set etat
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Etat $etat
     * @return Visite
     */
    public function setEtat(\KG\BeekeepingManagementBundle\Entity\Etat $etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Etat 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set agressivite
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Agressivite $agressivite
     * @return Visite
     */
    public function setAgressivite(\KG\BeekeepingManagementBundle\Entity\Agressivite $agressivite)
    {
        $this->agressivite = $agressivite;

        return $this;
    }

    /**
     * Get agressivite
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Agressivite 
     */
    public function getAgressivite()
    {
        return $this->agressivite;
    }
}
