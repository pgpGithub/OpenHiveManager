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

/**
 * Tache
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\TacheRepository")
 */
class Tache
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
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="taches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $colonie;    

    /**
     * @var Visite
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", inversedBy="taches")
     */
    private $visite; 
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Priorite")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner la priorité de la tâche")
     */
    private $priorite;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=100)
     */
    private $resume;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=500, nullable=true)
     * @Assert\Length(max=500, maxMessage="Le champ description ne peut dépasser {{ limit }} caractères") 
     */
    private $description;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
    
    /**
     * Constructor
     */
    public function __construct(Colonie $colonie)
    {
        $this->colonie = $colonie;     
        $this->setDate(new \DateTime()); 
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
     * @return Tache
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie)
    {
        $this->colonie = $colonie;
        $colonie->addTache($this);
        return $this;
    }

    /**
     * Get visite
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Visite
     */
    public function getVisite()
    {
        return $this->visite;
    }   

    /**
     * Set visite
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Visite $visite
     * @return Tache
     */
    public function setVisite(\KG\BeekeepingManagementBundle\Entity\Visite $visite)
    {
        $this->visite = $visite;
        $visite->addTache($this);
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
     * Set resume
     *
     * @param string $resume
     * @return Tache
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return Tache
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set priorite
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Priorite $priorite
     * @return Tache
     */
    public function setPriorite(\KG\BeekeepingManagementBundle\Entity\Priorite $priorite)
    {
        $this->priorite = $priorite;

        return $this;
    }

    /**
     * Get priorite
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Priorite
     */
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Visite
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
}
