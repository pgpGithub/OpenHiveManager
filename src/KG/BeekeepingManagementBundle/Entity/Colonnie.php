<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Colonnie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\ColonnieRepository")
 */
class Colonnie
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
     * @Assert\NotBlank(message="Veuillez remplir le nom de la colonnie")
     * @Assert\Length(max=25, maxMessage="Le nom de la colonnie ne peut dépasser {{ limit }} caractères") 
     */
    private $nom;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="anneeColonnie", type="datetime")
     * @Assert\NotBlank(message="Veuillez remplir l'année de naissance la colonnie")
     * @Assert\DateTime()
     */
    private $anneeColonnie;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Affectation")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'affectation de la colonnie")
     */
    private $affectation;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Provenance")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Assert\NotBlank(message="Veuillez sélectionner la provenance de la colonnie")
     */
    private $provenanceColonnie;

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
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Reine", inversedBy="colonnie", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $reine;
    
     /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonnie", inversedBy="colonniesFilles", cascade="persist")
     * @ORM\JoinColumn()
     * @Assert\Valid()
     */
    private $colonnieMere;

     /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonnie", mappedBy="colonnieMere", cascade="persist")
     * @Assert\Valid()
     */
    private $colonniesFilles;
    
     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", mappedBy="colonnie", cascade="persist")
     * @Assert\Valid()
     */
    private $ruche;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", mappedBy="colonnie")
     * @Assert\Valid()
     */
    private $visites;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="supprime", type="boolean")
     */
    private $supprime = false;
    
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
        $this->causes           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->colonniesFilles  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visites          = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Colonnie
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
     * @return Colonnie
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
     * Set provenanceColonnie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Provenance $provenanceColonnie
     * @return Colonnie
     */
    public function setProvenanceColonnie(\KG\BeekeepingManagementBundle\Entity\Provenance $provenanceColonnie)
    {
        $this->provenanceColonnie = $provenanceColonnie;

        return $this;
    }

    /**
     * Get provenanceColonnie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Provenance 
     */
    public function getProvenanceColonnie()
    {
        return $this->provenanceColonnie;
    }

    /**
     * Set colonnieMere
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonnie $colonnieMere
     * @return Colonnie
     */
    public function setColonnieMere(\KG\BeekeepingManagementBundle\Entity\Colonnie $colonnieMere = null)
    {
        $this->colonnieMere = $colonnieMere;

        return $this;
    }

    /**
     * Get colonnieMere
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonnie 
     */
    public function getColonnieMere()
    {
        return $this->colonnieMere;
    }

    /**
     * Set anneeColonnie
     *
     * @param \DateTime $anneeColonnie
     * @return Colonnie
     */
    public function setAnneeColonnie($anneeColonnie)
    {
        $this->anneeColonnie = $anneeColonnie;

        return $this;
    }

    /**
     * Get anneeColonnie
     *
     * @return \DateTime 
     */
    public function getAnneeColonnie()
    {
        return $this->anneeColonnie;
    }

    /**
     * Set etat
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Etat $etat
     * @return Colonnie
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
     * @return Colonnie
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
     * Set supprime
     *
     * @param string $supprime
     * @return Colonnie 
     */
    public function setSupprime($supprime)
    {
        $this->supprime = $supprime;

        return $this;
    }

    /**
     * Get supprime
     *
     * @return boolean 
     */
    public function getSupprime()
    {
        return $this->supprime;
    }          

    /**
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Colonnie
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
     * Add colonniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonnie $colonniesFilles
     * @return Colonnie
     */
    public function addColonniesFilles(\KG\BeekeepingManagementBundle\Entity\Colonnie $colonniesFilles)
    {
        $this->colonniesFilles[] = $colonniesFilles;

        return $this;
    }

    /**
     * Remove colonniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonnie $colonniesFilles
     */
    public function removeColonniesFilles(\KG\BeekeepingManagementBundle\Entity\Colonnie $colonniesFilles)
    {
        $this->colonniesFilles->removeElement($colonniesFilles);
    }

    /**
     * Get colonniesFilles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColonniesFilles()
    {
        return $this->colonniesFilles;
    }

    /**
     * Set morte
     *
     * @param boolean $morte
     * @return Colonnie
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
     * @return Colonnie
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
     * @return Colonnie
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
     * @return Colonnie
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
}
