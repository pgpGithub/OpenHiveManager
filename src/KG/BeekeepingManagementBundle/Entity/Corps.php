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
     * @var integer
     *
     * @ORM\Column(name="nbcadres", type="integer")
     * @Assert\NotBlank(message="Veuillez indiquer le nombre de cadres")
     */
    private $nbcadres;
    
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
     * Set nbcadres
     *
     * @param integer $nbcadres
     * @return Corps
     */
    public function setNbcadres($nbcadres)
    {
        $this->nbcadres = $nbcadres;

        return $this;
    }

    /**
     * Get nbcadres
     *
     * @return integer 
     */
    public function getNbcadres()
    {
        return $this->nbcadres;
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
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        $nbcadrestotal = $this->nbcouvain + $this->nbmiel;
        if ( $nbcadrestotal  > $this->nbcadres ) {
            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de miel est plus grande que le nombre de cadres') 
                   ->atPath('nbmiel')
                   ->addViolation();
        }
    }    
}
