<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Colonie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\ColonieRepository")
 */
class Colonie
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
     * @ORM\Column(name="nom", type="string", length=25)
     * @Assert\NotBlank(message="Veuillez remplir le nom de la colonie")
     * @Assert\Length(max=25, maxMessage="Le nom de la colonie ne peut dépasser {{ limit }} caractères") 
     */
    private $nom;

     /**
      * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Exploitation", inversedBy="colonies")
      * @ORM\JoinColumn(nullable=false)
      */
    private $exploitation;   
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="anneeColonie", type="datetime")
     * @Assert\NotBlank(message="Veuillez remplir l'année de naissance la colonie")
     * @Assert\DateTime()
     */
    private $anneeColonie;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Affectation")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'affectation de la colonie")
     */
    private $affectation;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Provenance")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Assert\NotBlank(message="Veuillez sélectionner la provenance de la colonie")
     */
    private $provenanceColonie;

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
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Reine", inversedBy="colonie", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $reine;
    
     /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="coloniesFilles", cascade="persist")
     * @ORM\JoinColumn()
     * @Assert\Valid()
     */
    private $colonieMere;

     /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", mappedBy="colonieMere", cascade="persist")
     * @Assert\Valid()
     */
    private $coloniesFilles;
    
     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", mappedBy="colonie", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $ruche;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", mappedBy="colonie", cascade={"remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $visites;
        
    /**
     * @var boolean
     *
     * @ORM\Column(name="morte", type="boolean")
     */
    private $morte = false;    

    /**
     * @var Cause
     * 
     * @ORM\ManyToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Cause")
     * @Assert\Valid()
     */
    private $causes;    

    /**
     * @var string
     *
     * @ORM\Column(name="autreCause", type="string", length=50, nullable=true)
     * @Assert\Length(max=50, maxMessage="La cause ne peut dépasser {{ limit }} caractères") 
     */
    private $autreCause;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->causes          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coloniesFilles  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visites         = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nom
     *
     * @param string $nom
     * @return Colonie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set affectation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Affectation $affectation
     * @return Colonie
     */
    public function setAffectation(\KG\BeekeepingManagementBundle\Entity\Affectation $affectation)
    {
        $this->affectation = $affectation;

        return $this;
    }

    /**
     * Get affectation
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Affectation 
     */
    public function getAffectation()
    {
        return $this->affectation;
    }

    /**
     * Set provenanceColonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Provenance $provenanceColonie
     * @return Colonie
     */
    public function setProvenanceColonie(\KG\BeekeepingManagementBundle\Entity\Provenance $provenanceColonie)
    {
        $this->provenanceColonie = $provenanceColonie;

        return $this;
    }

    /**
     * Get provenanceColonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Provenance 
     */
    public function getProvenanceColonie()
    {
        return $this->provenanceColonie;
    }

    /**
     * Set colonieMere
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonieMere
     * @return Colonie
     */
    public function setColonieMere(\KG\BeekeepingManagementBundle\Entity\Colonie $colonieMere = null)
    {
        $this->colonieMere = $colonieMere;

        return $this;
    }

    /**
     * Get colonieMere
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonieMere()
    {
        return $this->colonieMere;
    }

    /**
     * Set anneeColonie
     *
     * @param \DateTime $anneeColonie
     * @return Colonie
     */
    public function setAnneeColonie($anneeColonie)
    {
        $this->anneeColonie = $anneeColonie;

        return $this;
    }

    /**
     * Get anneeColonie
     *
     * @return \DateTime 
     */
    public function getAnneeColonie()
    {
        return $this->anneeColonie;
    }

    /**
     * Set etat
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Etat $etat
     * @return Colonie
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
     * @return Colonie
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
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Colonie
     */
    public function setRuche(\KG\BeekeepingManagementBundle\Entity\Ruche $ruche = null)
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
     * Add coloniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles
     * @return Colonie
     */
    public function addColoniesFilles(\KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles)
    {
        $this->coloniesFilles[] = $coloniesFilles;

        return $this;
    }

    /**
     * Remove coloniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles
     */
    public function removeColoniesFilles(\KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles)
    {
        $this->coloniesFilles->removeElement($coloniesFilles);
    }

    /**
     * Get coloniesFilles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColoniesFilles()
    {
        return $this->coloniesFilles;
    }

    /**
     * Set morte
     *
     * @param boolean $morte
     * @return Colonie
     */
    public function setMorte($morte)
    {
        $this->morte = $morte;

        return $this;
    }

    /**
     * Get morte
     *
     * @return boolean 
     */
    public function getMorte()
    {
        return $this->morte;
    }

    /**
     * Add cause
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Cause $cause
     * @return Colonie
     */
    public function addCause(\KG\BeekeepingManagementBundle\Entity\Cause $cause)
    {
        $this->causes[] = $cause;

        return $this;
    }

    /**
     * Remove cause
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Cause $cause
     */
    public function removeCause(\KG\BeekeepingManagementBundle\Entity\Cause $cause)
    {
        $this->causes->removeElement($cause);
    }

    /**
     * Get cause
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCauses()
    {
        return $this->causes;
    }

    /**
     * Set autreCause
     *
     * @param string $autreCause
     * @return Colonie
     */
    public function setAutreCause($autreCause)
    {
        $this->autreCause = $autreCause;

        return $this;
    }

    /**
     * Get autreCause
     *
     * @return string 
     */
    public function getAutreCause()
    {
        return $this->autreCause;
    }

    /**
     * Set reine
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Reine $reine
     * @return Colonie
     */
    public function setReine(\KG\BeekeepingManagementBundle\Entity\Reine $reine = null)
    {
        $this->reine = $reine;

        return $this;
    }

    /**
     * Get reine
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Reine 
     */
    public function getReine()
    {
        return $this->reine;
    }

    /**
     * Add visites
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Visite $visites
     * @return Ruche
     */
    public function addVisite(\KG\BeekeepingManagementBundle\Entity\Visite $visites)
    {
        $this->visites[] = $visites;

        return $this;
    }

    /**
     * Remove visites
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Visite $visites
     */
    public function removeVisite(\KG\BeekeepingManagementBundle\Entity\Visite $visites)
    {
        $this->visites->removeElement($visites);
    }

    /**
     * Get visites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVisites()
    {
        return $this->visites;
    }    

    /**
     * Set rucher
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucher
     * @return Colonie
     */
    public function setRucher(\KG\BeekeepingManagementBundle\Entity\Rucher $rucher)
    {
        $this->rucher = $rucher;

        return $this;
    }

    /**
     * Get rucher
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Rucher 
     */
    public function getRucher()
    {
        return $this->rucher;
    }

    /**
     * Set exploitation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Exploitation $exploitation
     * @return Colonie
     */
    public function setExploitation(\KG\BeekeepingManagementBundle\Entity\Exploitation $exploitation)
    {
        $this->exploitation = $exploitation;

        return $this;
    }

    /**
     * Get exploitation
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Exploitation 
     */
    public function getExploitation()
    {
        return $this->exploitation;
    }
}
