<?php

/*
 * Copyright (C) 2016 Kévin Grenèche < kevin.greneche at openhivemanager.org >
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

/**
 * Description of TrieRuche
 *
 * @author Kévin Grenèche < kevin.greneche at openhivemanager.org >
 */
namespace KG\BeekeepingManagementBundle\Twig\Extension;

use Doctrine\Common\Collections\ArrayCollection;

class TrieEmplacementParRuche extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('trie_emplacement_par_ruche', array($this, 'trier'))
        );
    }
 
    public function trier($value)
    {
        $iterator = $value->getIterator();
        $iterator->uasort(function ($a, $b) {
            if($a->getRuche() && $b->getRuche())
            {
                return ($a->getRuche()->getNom() > $b->getRuche()->getNom()) ? +1 : -1;
            }

            if(!$a->getRuche() && !$b->getRuche())
            {
                return 0;
            }        

            if(!$a->getRuche())
            {
                return +1;
            }         

            if(!$b->getRuche())
            {
                return -1;
            }               
        });
        $valueTriee = new ArrayCollection(iterator_to_array($iterator));        
        return $valueTriee;
    }
 
    public function getName()
    {
        return 'trie_emplacement_par_ruche_extension';
    }
 
}
