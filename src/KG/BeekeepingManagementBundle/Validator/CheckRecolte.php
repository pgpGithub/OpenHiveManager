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

/**
 * @Annotation
 */
class CheckRecolte extends Constraint
{
    public $message = 'Le nombre de cadres à récolter est supérieur au nombre de cadres plein présents dans la hausses';

    private $nbplein;
    
    /**
     * Constructor
     */
    public function __construct($nbplein)
    {
       $this->nbplein = $nbplein;
    }
    
    /**
     * Get nbcadres
     *
     * @return integer 
     */
    public function getNbplein()
    {
        return $this->nbplein;
    } 
}
