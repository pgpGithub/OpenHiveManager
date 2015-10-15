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
 * HausseVisite
 * 
 * @ORM\Table() 
 * @ORM\Entity
 */
class HausseVisite extends Hausse
{
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", inversedBy="hausses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $visite;

    /**
     * Constructor
     */
    public function __construct(Visite $visite, $nbplein = 0)
    {
        $ruche = $visite->getColonie()->getRuche();
        $this->visite = $visite;   
        
        
        if($nbplein){
            parent::setNbplein($nbplein);
        }
    }

    /**
     * Set visite
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $visite
     * @return Hausse
     */
    public function setVisite(\KG\BeekeepingManagementBundle\Entity\Ruche $visite)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Ruche 
     */
    public function getVisite()
    {
        return $this->visite;
    } 
}
