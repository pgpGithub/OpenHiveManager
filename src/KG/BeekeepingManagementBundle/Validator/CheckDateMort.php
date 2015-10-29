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
class CheckDateMort extends Constraint
{
    public $message1 = 'La date de la mort ne peut pas être située dans le futur';
    public $message2 = 'La date de la mort ne peut pas être plus ancienne que la date de naissance';
    public $message3 = 'La date de la mort ne peut pas être plus ancienne que la date de la dernière visite';
    public $message4 = 'La date de la mort ne peut pas être plus ancienne que la date du dernier remérage';
    public $message5 = 'La date de la mort ne peut pas être plus ancienne que la date de la dernière transhumance';
    
    private $colonie;
    
    /**
     * Constructor
     */
    public function __construct($colonie)
    {
       $this->colonie = $colonie;
    }
    
    /**
     * Get Colonie
     *
     * @return integer 
     */
    public function getColonie()
    {
        return $this->colonie;
    } 
}
