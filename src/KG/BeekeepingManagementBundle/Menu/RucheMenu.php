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
            
        // Ruche
        if( !$colonie->getMorte() ){
            $menu->addChild('Ruche', array(
                'route' => 'kg_beekeeping_management_home'
            )); 
                    
            $menu['Ruche']->addChild('Afficher', array(
            'route' => 'kg_beekeeping_management_view_ruche',
            'routeParameters' => array('ruche_id' => $colonie->getRuche()->getId())
            ));                  

            $menu['Ruche']->addChild('Modifier', array(
            'route' => 'kg_beekeeping_management_update_ruche',
            'routeParameters' => array('ruche_id' => $colonie->getRuche()->getId())
            )); 
            
            $filleExist = false;
            foreach( $colonie->getRemerages() as $remerage ){
                if( !$remerage->getReine()->getReinesFilles()->isEmpty() ){
                    $filleExist = true;
                    break;
                }
            }
                    
            if( $colonie->getVisites()->isEmpty() && $colonie->getRecoltes()->isEmpty() && !$filleExist ){    
                $menu['Ruche']->addChild('Supprimer', array(
                'route' => 'kg_beekeeping_management_delete_colonie',
                'routeParameters' => array('colonie_id' => $colonie->getId())
                )); 
            }               
        }        
         
        // Colonie
        $menu->addChild('Colonie', array(
            'route' => 'kg_beekeeping_management_home'
        )); 

        $menu['Colonie']->addChild('Afficher', array(
        'route' => 'kg_beekeeping_management_view_colonie',
        'routeParameters' => array('colonie_id' => $colonie->getId())
        )); 
        
        if( !$colonie->getMorte() ){
            $menu['Colonie']->addChild('Modifier', array(
            'route' => 'kg_beekeeping_management_update_colonie',
            'routeParameters' => array('colonie_id' => $colonie->getId())
            )); 

            if( $colonie->getRuche()->getCorps()->getNbCouvain() > 1 ){    
                $menu['Colonie']->addChild('Diviser', array(
                'route' => 'kg_beekeeping_management_diviser_colonie',
                'routeParameters' => array('colonie_id' => $colonie->getId())
                )); 
            }
            
            $menu['Colonie']->addChild('Déclarer morte', array(
            'route' => 'kg_beekeeping_management_tuer_colonie',
            'routeParameters' => array('colonie_id' => $colonie->getId())
            ));         
        }
        
        // Visites
        if( !$colonie->getMorte() || !$colonie->getVisites()->isEmpty() ){       
            $menu->addChild('Visites', array(
                'route' => 'kg_beekeeping_management_home'
            )); 
        }
        
        if( !$colonie->getMorte() ){
            if( !$colonie->getVisites()->isEmpty() ){
                if( date_format($colonie->getVisites()->last()->getDate(),"Y-m-d") < date_format(new \DateTime(),"Y-m-d") ){
                    $menu['Visites']->addChild('Créer', array(
                        'route' => 'kg_beekeeping_management_add_visite',
                        'routeParameters' => array('colonie_id' => $colonie->getId())
                    ));                     
                }
                else{
                    $menu['Visites']->addChild('Modifier dernière visite', array(
                        'route' => 'kg_beekeeping_management_update_visite',
                        'routeParameters' => array('visite_id' => $colonie->getVisites()->last()->getId())
                    ));                      
                }
            }else{
                $menu['Visites']->addChild('Créer', array(
                    'route' => 'kg_beekeeping_management_add_visite',
                    'routeParameters' => array('colonie_id' => $colonie->getId())
                ));                 
            }
        }
        
        if( !$colonie->getVisites()->isEmpty()){
            $menu['Visites']->addChild('Historique', array(
                'route' => 'kg_beekeeping_management_view_visites',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            ));            
        }

        
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
        
        // Récoltes
        if( !$colonie->getMorte() && ( !$colonie->getRecoltes()->isEmpty() || !$colonie->getRuche()->getHausses()->isEmpty() )){
            $menu->addChild('Récoltes', array(
                'route' => 'kg_beekeeping_management_home'
            )); 
        }
        
        if( !$colonie->getMorte() && !$colonie->getRuche()->getHausses()->isEmpty()){
            $menu['Récoltes']->addChild('Créer', array(
                'route' => 'kg_beekeeping_management_add_recolte',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            ));                  
        }
        
        if( !$colonie->getRecoltes()->isEmpty()){
            $menu['Récoltes']->addChild('Historique', array(
                'route' => 'kg_beekeeping_management_view_recoltes',
                'routeParameters' => array('colonie_id' => $colonie->getId())
            )); 
        }
        
        // Transhumances
        if( !$colonie->getMorte() || !$colonie->getTranshumances()->isEmpty() ){
            $menu->addChild('Transhumances', array(
                'route' => 'kg_beekeeping_management_home'
            )); 
        }
        
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