<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Corps
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Corps
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
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\TypeRuche")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner le type de la ruche")
     */
    private $type; 
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\SousTypeRuche")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner le nombre de cadres de la ruche")
     */
    private $soustype; 
    
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
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", inversedBy="corps")
     */
    private $ruche;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cadres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Corps
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
     * Set nbcouvain
     *
     * @param integer $nbcouvain
     * @return Corps
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
     * @return Corps
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
     * Set type
     *
     * @param \KG\BeekeepingManagementBundle\Entity\TypeRuche $type
     * @return Corps
     */
    public function setType(\KG\BeekeepingManagementBundle\Entity\TypeRuche $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \KG\BeekeepingManagementBundle\Entity\TypeRuche 
     */
    public function getType()
    {
        return $this->type;
    }    
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        $nbcadrestotal = $this->nbcouvain + $this->nbmiel;
        if ( $nbcadrestotal  > $this->soustype->getNbCadres() ) {
            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de miel est plus grande que le nombre de cadres') 
                   ->atPath('nbmiel')
                   ->addViolation();
        }
    }    

    /**
     * Set soustype
     *
     * @param \KG\BeekeepingManagementBundle\Entity\SousTypeRuche $soustype
     * @return Corps
     */
    public function setSoustype(\KG\BeekeepingManagementBundle\Entity\SousTypeRuche $soustype)
    {
        $this->soustype = $soustype;

        return $this;
    }

    /**
     * Get soustype
     *
     * @return \KG\BeekeepingManagementBundle\Entity\SousTypeRuche 
     */
    public function getSoustype()
    {
        return $this->soustype;
    }
}
