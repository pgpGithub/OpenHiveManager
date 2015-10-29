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
use Symfony\Component\Validator\ConstraintValidator;

class CheckDateMortValidator extends ConstraintValidator
{   
    public function validate($value, Constraint $constraint)
    {
        $today = new \DateTime();
        
        if( $value > $today ){
            $this->context->addViolation($constraint->message1);
        }

        if( $value <= $constraint->getColonie()->getDateColonie() ){
            $this->context->addViolation($constraint->message2);         
        }

        if( !$constraint->getColonie()->getVisites()->isEmpty() ){
            if( $value <= $constraint->getColonie()->getVisites()->last()->getDate() ){
                $this->context->addViolation($constraint->message3);
            }            
        }

        if( !$constraint->getColonie()->getRemerages()->isEmpty() ){
            if( $value <= $constraint->getColonie()->getRemerages()->last()->getDate() ){
                $this->context->addViolation($constraint->message4);     
            }            
        }            

        if( !$constraint->getColonie()->getTranshumances()->isEmpty() ){
            if( $value <= $constraint->getColonie()->getTranshumances()->last()->getDate() ){
                $this->context->addViolation($constraint->message5);
            }            
        }                     
    }
} 