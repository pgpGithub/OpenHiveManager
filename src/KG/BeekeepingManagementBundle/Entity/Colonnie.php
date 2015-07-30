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
      * @var Exploitation 
      * 
      * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Exploitation", inversedBy="colonnies")
      * @ORM\JoinColumn(nullable=false)
      */
    private $exploitation;
    
    /**
     * @var Race
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Race")
     * @ORM\JoinColumn(nullable=false)
     */
    private $race;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez remplir le nom de la colonnie")
     * @Assert\Length(max=25, maxMessage="Le nom de la colonnie ne peut dépasser {{ limit }} caractères") 
     */
    private $nom;

     /**
     * @var integer
     *
     * @ORM\Column(name="anneeColonnie", type="integer")
     */
    private $anneeColonnie;

    /**
     * @var Affectation
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Affectation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $affectation;

    /**
     * @var Provenance
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Provenance")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provenanceColonnie;

     /**
     * @var integer
     *
     * @ORM\Column(name="anneeReine", type="integer")
     */
    private $anneeReine;

    /**
     * @var boolean
     *
     * @ORM\Column(name="clippage", type="boolean")
     */
    private $clippage;    

    /**
     * @var Marquage
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Marquage")
     * @ORM\JoinColumn()
     */
    private $marquage;    

    /**
     * @var Provenance
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Provenance")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provenanceReine;
    
     /**
     * @var Colonnie
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonnie")
     * @ORM\JoinColumn()
     */
    private $colonnieMere;
    
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
     * Set race
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Race $race
     * @return Colonnie
     */
    public function setRace(\KG\BeekeepingManagementBundle\Entity\Race $race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Race 
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set exploitation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Exploitation $exploitation
     * @return Colonnie
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
     * Set anneeColonnie
     *
     * @param integer $anneeColonnie
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
     * @return integer 
     */
    public function getAnneeColonnie()
    {
        return $this->anneeColonnie;
    }

    /**
     * Set anneeReine
     *
     * @param integer $anneeReine
     * @return Colonnie
     */
    public function setAnneeReine($anneeReine)
    {
        $this->anneeReine = $anneeReine;

        return $this;
    }

    /**
     * Get anneeReine
     *
     * @return integer 
     */
    public function getAnneeReine()
    {
        return $this->anneeReine;
    }

    /**
     * Set clippage
     *
     * @param boolean $clippage
     * @return Colonnie
     */
    public function setClippage($clippage)
    {
        $this->clippage = $clippage;

        return $this;
    }

    /**
     * Get clippage
     *
     * @return boolean 
     */
    public function getClippage()
    {
        return $this->clippage;
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
     * Set marquage
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Marquage $marquage
     * @return Colonnie
     */
    public function setMarquage(\KG\BeekeepingManagementBundle\Entity\Marquage $marquage = null)
    {
        $this->marquage = $marquage;

        return $this;
    }

    /**
     * Get marquage
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Marquage 
     */
    public function getMarquage()
    {
        return $this->marquage;
    }

    /**
     * Set provenanceReine
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Provenance $provenanceReine
     * @return Colonnie
     */
    public function setProvenanceReine(\KG\BeekeepingManagementBundle\Entity\Provenance $provenanceReine)
    {
        $this->provenanceReine = $provenanceReine;

        return $this;
    }

    /**
     * Get provenanceReine
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Provenance 
     */
    public function getProvenanceReine()
    {
        return $this->provenanceReine;
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
}
