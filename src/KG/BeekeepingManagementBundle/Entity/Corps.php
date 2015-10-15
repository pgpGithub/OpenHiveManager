<?php

/* 
 * Copyright (C) 2015 Kévin Grenèche < kevin.greneche at openhivemanager.org >
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
     * @var integer
     *
     * @ORM\Column(name="nbcadres", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de cadres présents dans la ruche ne peut pas être négatif"
     * )) 
     * @Assert\NotBlank(message="Veuillez indiquer le nombre de cadres présents dans la ruche")
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
     * @ORM\Column(name="nbnourriture", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de cadres de nourriture ne peut pas être négatif"
     * )
     * @Assert\NotBlank(message="Veuillez indiquer le nombre de cadres de nourriture présents dans la ruche")
     */
    private $nbnourriture;      
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", mappedBy="corps")
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
     * Set nbnourriture
     *
     * @param integer $nbnourriture
     * @return Corps
     */
    public function setNbnourriture($nbnourriture)
    {
        $this->nbnourriture = $nbnourriture;

        return $this;
    }

    /**
     * Get nbnourriture
     *
     * @return integer 
     */
    public function getNbnourriture()
    {
        return $this->nbnourriture;
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
        $nbcadrestotal = $this->nbcouvain + $this->nbnourriture;
        if ( $nbcadrestotal  > $this->getNbCadres() ) {
            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de nourriture est plus grande que le nombre de cadres total') 
                   ->atPath('nbnourriture')
                   ->addViolation();
            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de nourriture est plus grande que le nombre de cadres total') 
                   ->atPath('nbcouvain')
                   ->addViolation();
            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de nourriture est plus grande que le nombre de cadres total') 
                   ->atPath('nbcadres')
                   ->addViolation();
        }
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
}
