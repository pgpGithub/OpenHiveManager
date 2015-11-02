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
 * @ORM\Entity
 */
class Reine
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
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Race")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Assert\NotBlank(message="Veuillez sélectionner la race")
     */
    private $race;
   
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="anneeReine", type="datetime")
     * @Assert\NotBlank(message="Veuillez remplir l'année de naissance de la reine") 
     * @Assert\DateTime()
     */
    private $anneeReine;

    /**
     * @var boolean
     *
     * @ORM\Column(name="clippage", type="boolean", nullable=true)
     */
    private $clippage;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="marquage", type="boolean", nullable=true)
     */
    private $marquage;    

     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Remerage", mappedBy="reine")
     * @Assert\Valid()
     */
    private $remerage;

     /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Reine", inversedBy="reinesFilles")
     */
    private $reineMere;

     /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Reine", mappedBy="reineMere")
     */
    private $reinesFilles;

     /**
     * Constructor
     */
    public function __construct()
    {
        $this->reinesFilles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Remerer
     *
     * @return Reine
     */
    public function remerer()
    {
        $reine = new Reine();
        $reine->setRace($this->getRace());

        return $reine;
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
     * Set anneeReine
     *
     * @param \DateTime $anneeReine
     * @return Reine
     */
    public function setAnneeReine($anneeReine)
    {
        $this->anneeReine = $anneeReine;

        return $this;
    }

    /**
     * Get anneeReine
     *
     * @return \DateTime 
     */
    public function getAnneeReine()
    {
        return $this->anneeReine;
    }

    /**
     * Set clippage
     *
     * @param boolean $clippage
     * @return Reine
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
     * Set exploitation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Exploitation $exploitation
     * @return Reine
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
     * Set race
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Race $race
     * @return Reine
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
     * Set marquage
     *
     * @param boolean $marquage
     * @return Reine
     */
    public function setMarquage($marquage)
    {
        $this->marquage = $marquage;

        return $this;
    }

    /**
     * Get marquage
     *
     * @return boolean 
     */
    public function getMarquage()
    {
        return $this->marquage;
    }

    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {              
        $today = new \DateTime();
        
        if( $this->anneeReine > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('anneeReine')
                   ->addViolation();            
        }
       
    }        

    /**
     * Set remerage
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Remerage $remerage
     * @return Reine
     */
    public function setRemerage(\KG\BeekeepingManagementBundle\Entity\Remerage $remerage)
    {
        $this->remerage = $remerage;

        return $this;
    }

    /**
     * Get remerage
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Remerage 
     */
    public function getRemerage()
    {
        return $this->remerage;
    }

    /**
     * Set reineMere
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Reine $reineMere
     * @return Reine
     */
    public function setReineMere(\KG\BeekeepingManagementBundle\Entity\Reine $reineMere)
    {
        $this->reineMere = $reineMere;
        $reineMere->addReinesFille($this);
        return $this;
    }

    /**
     * Get reineMere
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Reine 
     */
    public function getReineMere()
    {
        return $this->reineMere;
    }

    /**
     * Add reinesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Reine $reinesFilles
     * @return Reine
     */
    public function addReinesFille(\KG\BeekeepingManagementBundle\Entity\Reine $reinesFilles)
    {
        $this->reinesFilles[] = $reinesFilles;
        return $this;
    }

    /**
     * Remove reinesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Reine $reinesFilles
     */
    public function removeReinesFille(\KG\BeekeepingManagementBundle\Entity\Reine $reinesFilles)
    {
        $this->reinesFilles->removeElement($reinesFilles);
    }

    /**
     * Get reinesFilles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReinesFilles()
    {
        return $this->reinesFilles;
    }
}
