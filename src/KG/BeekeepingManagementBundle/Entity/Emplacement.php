<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Emplacement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\EmplacementRepository")
 */
class Emplacement
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="soleil", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Le taux d'exposition au soleil ne peut pas être négatif",
     *      maxMessage = "Le taux d'exposition au soleil ne peut pas dépasser 100%"
     * )
     */
    private $soleil;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Orientation")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'orientation de l'emplacement")
     */
    private $orientation;

     /**
      * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher", inversedBy="emplacements")
      * @ORM\JoinColumn(nullable=false)
      */
    private $rucher;    

     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", mappedBy="emplacement", cascade={"remove"}, orphanRemoval=true)
     */
    private $ruche;
    
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
     * @return Emplacement
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
     * Set soleil
     *
     * @param integer $soleil
     * @return Emplacement
     */
    public function setSoleil($soleil)
    {
        $this->soleil = $soleil;

        return $this;
    }

    /**
     * Get soleil
     *
     * @return integer 
     */
    public function getSoleil()
    {
        return $this->soleil;
    }

    /**
     * Set rucher
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucher
     * @return Emplacement
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
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Emplacement
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
     * Set orientation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Orientation $orientation
     * @return Emplacement
     */
    public function setOrientation(\KG\BeekeepingManagementBundle\Entity\Orientation $orientation)
    {
        $this->orientation = $orientation;

        return $this;
    }

    /**
     * Get orientation
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Orientation 
     */
    public function getOrientation()
    {
        return $this->orientation;
    }
}
