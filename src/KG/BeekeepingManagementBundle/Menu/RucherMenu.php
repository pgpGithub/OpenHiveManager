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
        $rucher = $options["rucher"];           

        $menu->addChild('.icon-chevron-left Retour à l\'exploitation', array(
            'route' => 'kg_beekeeping_management_home'
        ));              
        
        // Rucher
        $titleRucher = '.icon-building Rucher';
        $menu->addChild($titleRucher, array(
            'route' => 'kg_beekeeping_management_home'
        )); 

        $menu[$titleRucher]->addChild('.icon-eye Afficher', array(
            'route' => 'kg_beekeeping_management_view_rucher',
            'routeParameters' => array('rucher_id' => $rucher->getId())
        ));    
        
        $menu[$titleRucher]->addChild('.icon-pencil Modifier le rucher', array(
            'route' => 'kg_beekeeping_management_update_rucher',
            'routeParameters' => array('rucher_id' => $rucher->getId())
        ));         

        $qrcode_permitted = false;
        
        foreach( $rucher->getEmplacements() as $emplacement ){
            if( $emplacement->getRuche() ){            
                $qrcode_permitted = true;
                break;
            }
        }        

        if( $qrcode_permitted ){
            $menu[$titleRucher]->addChild('.icon-qrcode QR Code', array(
                'route' => 'kg_beekeeping_management_print_all_qr_code',
                'routeParameters' => array('rucher_id' => $rucher->getId())
            ));         
        }
        
        $delete_permitted = true;
        
        foreach( $rucher->getEmplacements() as $emplacement ){
            if(    !$emplacement->getTranshumancesfrom()->isEmpty() 
                || !$emplacement->getTranshumancesto()->isEmpty()
                || $emplacement->getRuche() ){
                
                $delete_permitted = false;
                break;
            }
        }
        
        if( $delete_permitted ){
            $menu[$titleRucher]->addChild('.icon-trash Supprimer le rucher', array(
                'route' => 'kg_beekeeping_management_delete_rucher',
                'routeParameters' => array('rucher_id' => $rucher->getId())
            ));
        }

        // Colonies mortes
        $morteExist = false;
        foreach( $rucher->getRuches() as $ruche ){
            if( $ruche->getColonie()->getMorte() ){
                $morteExist = true;
                break;
            }
        }
        
        if( $morteExist ){
            $menu->addChild('.icon-heartbeat Colonies mortes', array(
                'route' => 'kg_beekeeping_management_view_colonies_mortes',
                'routeParameters' => array('rucher_id' => $rucher->getId())
            ));              
        }
        
        // Emplacement
        $menu->addChild('.icon-plus Créer un emplacement', array(
            'route' => 'kg_beekeeping_management_add_emplacement',
            'routeParameters' => array('rucher_id' => $rucher->getId())
        ));          
 
        return $menu;
    }
}
