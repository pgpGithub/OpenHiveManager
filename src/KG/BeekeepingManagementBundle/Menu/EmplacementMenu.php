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

class EmplacementMenu extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $emplacement = $options["emplacement"];           

        $menu->addChild('.icon-chevron-left Retour au rucher', array(
            'route' => 'kg_beekeeping_management_view_rucher',
            'routeParameters' => array('rucher_id' => $emplacement->getRucher()->getId())
        ));       
        
        $menu->addChild('.icon-pencil Modifier l\'emplacement', array(
            'route' => 'kg_beekeeping_management_update_emplacement',
            'routeParameters' => array('emplacement_id' => $emplacement->getId())
        ));         
        
        if( !$emplacement->getRuche() ){
            $menu->addChild('.icon-trash Supprimer l\'emplacement', array(
                'route' => 'kg_beekeeping_management_delete_emplacement',
                'routeParameters' => array('emplacement_id' => $emplacement->getId())
            ));
        }
 
        return $menu;
    }
}