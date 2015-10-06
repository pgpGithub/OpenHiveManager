<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Hausse
/* @ORM\MappedSuperclass 
 */
abstract class Hausse
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
     * @ORM\Column(name="nbplein", type="integer")
     */
    private $nbplein = 0;   
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nbcadres", type="integer")
     */
    private $nbcadres;     
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\TypeRuche")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner le type de la hausse")
     */
    private $type; 
    
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
     * Set nbplein
     *
     * @param integer $nbplein
     * @return Hausse
     */
    public function setNbplein($nbplein)
    {
        $this->nbplein = $nbplein;

        return $this;
    }

    /**
     * Get nbplein
     *
     * @return integer 
     */
    public function getNbplein()
    {
        return $this->nbplein;
    }
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        if ( $this->nbcadres  < $this->nbplein ) {
            $context
                   ->buildViolation('Le nombre de cadres plein est plus grand que le nombre de cadres présents dans la hausse') 
                   ->atPath('nbplein')
                   ->addViolation();
        }
    }       

    /**
     * Set nbcadres
     *
     * @param integer $nbcadres
     * @return Hausse
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
     * Set type
     *
     * @param \KG\BeekeepingManagementBundle\Entity\TypeRuche $type
     * @return Hausse
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
}
