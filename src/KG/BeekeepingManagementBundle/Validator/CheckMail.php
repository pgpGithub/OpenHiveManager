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

namespace KG\BeekeepingManagementBundle\Validator;

use Symfony\Component\Validator\Constraint;
use KG\BeekeepingManagementBundle\Entity\Exploitation;

/**
 * @Annotation
 */
class CheckMail extends Constraint
{
    public $message_1 = "Cette adresse mail ne correspond à aucun utilisateur inscrit";
    public $message_2 = "Cet utilisateur collabore déjà sur cette exploitation";

    private $exploitation;
    
    /**
     * Constructor
     */
    public function __construct(Exploitation $exploitation)
    {
       $this->exploitation = $exploitation;
    }
    
    /**
     * Get exploitation
     *
     * @return Exploitation 
     */
    public function getExploitation()
    {
        return $this->exploitation;
    } 
    
    public function validatedBy()
    {
        return 'kg_beekeeping_management_checkmail';
    }
    
}
