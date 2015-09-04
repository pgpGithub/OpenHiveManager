<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

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
}
