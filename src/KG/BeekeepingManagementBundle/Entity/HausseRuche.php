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
 * HausseRuche
 * 
 * @ORM\Table() 
 * @ORM\Entity
 */
class HausseRuche extends Hausse
{
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", inversedBy="hausses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruche;

    /**
     * Constructor
     */
    public function __construct(HausseVisite $hausse)
    {
        parent::setNbcadres($hausse->getNbcadres());
        parent::setNbplein($hausse->getNbplein());
        $this->ruche = $hausse->getVisite()->getColonie()->getRuche();
    }

    /**
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Hausse
     */
    public function setRuche(\KG\BeekeepingManagementBundle\Entity\Ruche $ruche)
    {
        $this->ruche = $ruche;
        $ruche->addHauss($this);
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
}
