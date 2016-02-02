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
     * @ORM\Column(name="numero", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez remplir le numéro de l'emplacement")
     */
    private $numero = "?";

     /**
      * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher", inversedBy="emplacements")
      * @ORM\JoinColumn(nullable=false)
      */
    private $rucher;    

     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", mappedBy="emplacement")
     */
    private $ruche; 
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Transhumance", mappedBy="emplacementfrom")
     */
    private $transhumancesfrom;
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Transhumance", mappedBy="emplacementto")
     */
    private $transhumancesto;    
    
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
     * @param string $numero
     * @return Emplacement
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
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
     * Constructor
     */
    public function __construct(Rucher $rucher = null)
    {
        $this->transhumancesfrom = new \Doctrine\Common\Collections\ArrayCollection();
        $this->transhumancesto = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rucher = $rucher;    
    }

    /**
     * Add transhumancesfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom
     * @return Emplacement
     */
    public function addTranshumancesfrom(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom)
    {
        $transhumancesfrom->setEmplacementfrom($this);
        $this->transhumancesfrom[] = $transhumancesfrom;
        
        return $this;
    }

    /**
     * Remove transhumancesfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom
     */
    public function removeTranshumancesfrom(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesfrom)
    {
        $this->transhumancesfrom->removeElement($transhumancesfrom);
    }

    /**
     * Get transhumancesfrom
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranshumancesfrom()
    {
        return $this->transhumancesfrom;
    }

    /**
     * Add transhumancesto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto
     * @return Emplacement
     */
    public function addTranshumancesto(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto)
    {
        $transhumancesto->setEmplacementfrom($this);        
        $this->transhumancesto[] = $transhumancesto;

        return $this;
    }

    /**
     * Remove transhumancesto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto
     */
    public function removeTranshumancesto(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumancesto)
    {
        $this->transhumancesto->removeElement($transhumancesto);
    }

    /**
     * Get transhumancesto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranshumancesto()
    {
        return $this->transhumancesto;
    }

    public function isEmpty()
    {
        $empty = true;

        if( $this->getRuche() ){
            $empty = false;
        }
        
        return $empty;         
    }    
    
    public function canBeDeleted()
    {
        $permitted = true;
        
        if( $this->getRuche() ){
            $permitted = false;
        }
        
        return $permitted;        
    }

    public function canBeUpdated()
    {
        $permitted = true;
        
        if( !$this->getRucher()->getNumerotation() ){
            $permitted = false;
        }
        
        return $permitted;        
    }
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {        
        foreach( $this->getRucher()->getEmplacements() as $emplacement ){
            if( strtoupper($emplacement->getNumero()) == strtoupper($this->numero) && $emplacement->getId() != $this->getId() ){
                $context
                    ->buildViolation('Un autre emplacement porte déjà ce numero dans le rucher') 
                    ->atPath('numero')
                    ->addViolation();
                break;
            }
        }
    }     
}
