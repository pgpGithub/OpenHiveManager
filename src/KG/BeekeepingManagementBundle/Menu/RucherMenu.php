<?php

/*
 * Copyright (C) 2015 kevin
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

namespace KG\BeekeepingManagementBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class RucherMenu extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $ruchers = $options["exploitation"]->getRuchers();
        
        $title = 'Rucher';
        if( $options["rucher"] ){
            $title = $options["rucher"]->getNom();
        }
        
        $menu->addChild($title, array(
            'route' => 'kg_beekeeping_management_home'
        ));      
        
        foreach( $ruchers as $rucher ){
            $menu[$title]->addChild($rucher->getNom(), array(
                'route' => 'kg_beekeeping_management_view_rucher',
                'routeParameters' => array('rucher_id' => $rucher->getId())
            ));              
        }

        $menu[$title]->addChild('Créer un rucher', array(
            'route' => 'kg_beekeeping_management_add_rucher',
            'routeParameters' => array('exploitation_id' => $options["exploitation"]->getId())
        ));          
        
        return $menu;
    }
}