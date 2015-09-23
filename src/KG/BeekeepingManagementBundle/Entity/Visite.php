<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @var Colonie
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="visites")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $colonie;    
    
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
     * @ORM\Column(name="pollen", type="boolean")
     */
    private $pollen = false;    

    /**
     * @var integer
     *
     * @ORM\Column(name="nbcouvain", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de cadres de couvain ne peut pas être négatif"
     * )
     * @Assert\NotBlank(message="Veuillez indiquer le nombre de cadres de couvain présents dans la ruche")
     */
    private $nbcouvain;    

    /**
     * @var integer
     *
     * @ORM\Column(name="nbmiel", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de cadres de miel ne peut pas être négatif"
     * )
     * @Assert\NotBlank(message="Veuillez indiquer le nombre de cadres de miel présents dans la ruche")
     */
    private $nbmiel;  
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="celroyales", type="boolean")
     */
    private $celroyales = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Etat")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'état de la colonie")
     */
    private $etat;
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Agressivite")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'agressivité de la colonie")
     */
    private $agressivite;

    /**
     * @var string
     *
     * @ORM\Column(name="nourrissement", type="string", length=50, nullable=true)  
     * @Assert\Length(max=50, maxMessage="Le type de nourrissement ne peut dépasser {{ limit }} caractères")
     */
    private $nourrissement;

    /**
     * @var string
     *
     * @ORM\Column(name="traitement", type="string", length=50, nullable=true)  
     * @Assert\Length(max=50, maxMessage="Le type de traitement ne peut dépasser {{ limit }} caractères")
     */
    private $traitement; 

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text", length=300, nullable=true)
     * @Assert\Length(max=300, maxMessage="Le champ observations ne peut dépasser {{ limit }} caractères") 
     */
    private $observations;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
    
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
     * Set colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Visite
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie)
    {
        $this->colonie = $colonie;

        return $this;
    }

    /**
     * Get colonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonie()
    {
        return $this->colonie;
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

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Visite
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set pollen
     *
     * @param boolean $pollen
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
     * @return boolean 
     */
    public function getPollen()
    {
        return $this->pollen;
    }

    /**
     * Set celroyales
     *
     * @param boolean $celroyales
     * @return Visite
     */
    public function setCelroyales($celroyales)
    {
        $this->celroyales = $celroyales;

        return $this;
    }

    /**
     * Get celroyales
     *
     * @return boolean 
     */
    public function getCelroyales()
    {
        return $this->celroyales;
    }

    /**
     * Set nbcouvain
     *
     * @param integer $nbcouvain
     * @return Visite
     */
    public function setNbcouvain($nbcouvain)
    {
        $this->nbcouvain = $nbcouvain;

        return $this;
    }

    /**
     * Get nbcouvain
     *
     * @return integer 
     */
    public function getNbcouvain()
    {
        return $this->nbcouvain;
    }

    /**
     * Set nbmiel
     *
     * @param integer $nbmiel
     * @return Visite
     */
    public function setNbmiel($nbmiel)
    {
        $this->nbmiel = $nbmiel;

        return $this;
    }

    /**
     * Get nbmiel
     *
     * @return integer 
     */
    public function getNbmiel()
    {
        return $this->nbmiel;
    }
    
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        $nbcadrestotal = $this->nbcouvain + $this->nbmiel;
        if ( $nbcadrestotal  > $this->getColonie()->getRuche()->getCorps()->getSoustype()->getNbCadres()) {
            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de miel est plus grande que le nombre de cadres') 
                   ->atPath('nbmiel')
                   ->addViolation();
        }
    }     
}
