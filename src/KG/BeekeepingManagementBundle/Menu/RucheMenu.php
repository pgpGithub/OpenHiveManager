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

class RucheMenu extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $colonie = $options["colonie"];

        $menu->addChild('Ruche', array(
            'route' => 'kg_beekeeping_management_view_ruche',
            'routeParameters' => array('ruche_id' => $colonie->getRuche()->getId())
        ));
        
        $menu->addChild('Colonie', array(
            'route' => 'kg_beekeeping_management_view_colonie',
            'routeParameters' => array('colonie_id' => $colonie->getId())
        ));
        
        
        // Visites
        $menu->addChild('Visites', array(
            'route' => 'kg_beekeeping_management_home'
        )); 
        
        if( !$colonie->getMorte() && date_format($colonie->getVisites()->last()->getDate(),"Y-m-d") < date_format(new \DateTime(),"Y-m-d")){
            $menu['Visites']->addChild('Créer', array(
                'route' => 'kg_beekeeping_management_add_visite',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            )); 
        }
        
        /*if( !$colonie->getVisites()->isEmpty()){
            $menu['Visites']->addChild('Historique', array(
                'route' => 'kg_beekeeping_management_view_visites',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            ));            
        }*/

        
        // Remérages
        $menu->addChild('Remérages', array(
            'route' => 'kg_beekeeping_management_home'
        ));                  
        
        if( !$colonie->getMorte() ){
            $menu['Remérages']->addChild('Créer', array(
                'route' => 'kg_beekeeping_management_add_remerage',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            ));                  
        }
        
        $menu['Remérages']->addChild('Historique', array(
            'route' => 'kg_beekeeping_management_view_remerages',
            'routeParameters' => array('colonie_id' => $colonie->getId())
        ));           
        
        
        // Transhumances
        $menu->addChild('Transhumances', array(
            'route' => 'kg_beekeeping_management_home'
        )); 

        if( !$colonie->getMorte() ){
            $menu['Transhumances']->addChild('Créer', array(
                'route' => 'kg_beekeeping_management_add_transhumance',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            ));                  
        }
        
        if( !$colonie->getTranshumances()->isEmpty()){
            $menu['Transhumances']->addChild('Historique', array(
                'route' => 'kg_beekeeping_management_view_transhumances',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            )); 
        }

        return $menu;
    }
}