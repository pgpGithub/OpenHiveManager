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

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Reine
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\RemerageRepository")
 */
class Remerage
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
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Reine", inversedBy="remerage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false) 
     * @Assert\Valid()
     */
    private $reine;      
    
     /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="remerages")
     * @ORM\JoinColumn(nullable=false) 
     */
    private $colonie;
     
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="naturel", type="boolean")
     */
    private $naturel;       

    /**
     * Constructor
     */
    public function __construct(Reine $reine, Colonie $colonie, $naturel = null)
    {
        $this->setReine($reine);
        $this->setColonie($colonie);
        $this->naturel = $naturel;     
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
     * Set date
     *
     * @param \DateTime $date
     * @return Remerage
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
     * Set naturel
     *
     * @param boolean $naturel
     * @return Remerage
     */
    public function setNaturel($naturel)
    {
        $this->naturel = $naturel;

        return $this;
    }

    /**
     * Get naturel
     *
     * @return boolean 
     */
    public function getNaturel()
    {
        return $this->naturel;
    }

    /**
     * Set reine
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Reine $reine
     * @return Remerage
     */
    public function setReine(\KG\BeekeepingManagementBundle\Entity\Reine $reine)
    {
        $this->reine = $reine;
        $reine->setRemerage($this);
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
     * Set colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Remerage
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie = null)
    {
        $this->colonie = $colonie;
        $colonie->addRemerage($this);
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
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {              
        $today = new \DateTime();
        
        if( $this->date > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('date')
                   ->addViolation();            
        }
        
        // Si on est pas sur le remérage de la création de la colonie et que le remérage est articiel
        if($this->getColonie()->getRemerages()->count() > 1 && !$this->naturel){     
            if( $this->reine->getAnneeReine() ){
                if(  $this->date->diff($this->reine->getAnneeReine())->format('%y') > 5 ){
                    $context
                           ->buildViolation('La date de remérage est trop éloignée de l\'année de la reine') 
                           ->atPath('date')
                           ->addViolation();                      
                } 
            }
        }
        
        foreach( $this->getColonie()->getRemerages() as $lastRemerage ){
            if ( $this->date < $lastRemerage->getDate()  && $lastRemerage->getId() != $this->getId() ){                
                $context
                       ->buildViolation('La date ne peut pas être antérieur ou égale à celle d\'un ancien remérage') 
                       ->atPath('date')
                       ->addViolation();
            }            
        }        
    }     
}
