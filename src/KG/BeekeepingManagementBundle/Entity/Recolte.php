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
 * RecolteRuche
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Recolte
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
     * @var Colonie
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="recoltes")
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
     * @var integer
     *
     * @ORM\Column(name="nbcadres", type="integer")
     */
    private $nbcadres; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=25, unique=true)  
     * @Assert\NotBlank(message="Veuillez remplir le type de miel récolté")
     * @Assert\Length(max=25, maxMessage="Le type de miel ne peut dépasser {{ limit }} caractères")
     */
    private $typemiel;

    /**
     * Constructor
     */
    public function __construct(Colonie $colonie)
    {
        $this->date = new \DateTime();
        $this->colonie = $colonie;
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
     * Set colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return RecolteRuche
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie)
    {
        $this->colonie = $colonie;
        $colonie->addRecoltes($this);
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

    }     

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Recolte
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
     * Set nbcadres
     *
     * @param integer $nbcadres
     * @return Recolte
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
     * Set typemiel
     *
     * @param string $typemiel
     * @return Recolte
     */
    public function setTypemiel($typemiel)
    {
        $this->typemiel = $typemiel;

        return $this;
    }

    /**
     * Get typemiel
     *
     * @return string 
     */
    public function getTypemiel()
    {
        return $this->typemiel;
    }
}
