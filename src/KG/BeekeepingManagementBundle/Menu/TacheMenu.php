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

class TacheMenu extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $tache = $options["tache"];

        $menu->addChild('.icon-chevron-left Retour à la ruche', array(
            'route' => 'kg_beekeeping_management_view_ruche',
            'routeParameters' => array('ruche_id' => $tache->getColonie()->getRuche()->getId())
        ));   

        //Dupliquer
        $titleDupliquer = '.icon-files-o Dupliquer ';
        $menu->addChild($titleDupliquer, array(
            'route' => 'kg_beekeeping_management_home'
        ));         
        
        $ruchers = $tache->getColonie()->getRuche()->getRucher()->getExploitation()->getRuchers();
        foreach( $ruchers as $rucher){
            $rucheExist = false;
            foreach( $rucher->getEmplacements() as $emplacement ){
                if( $emplacement->getRuche() ){
                    $rucheExist = true;
                    break;
                }
            }
            if( $rucheExist ){
                $menu[$titleDupliquer]->addChild('.icon-long-arrow-right vers '.$rucher->getNom(), array(
                    'route' => 'kg_beekeeping_management_duplicate_tache',
                    'routeParameters' => array('tache_id'  => $tache->getId(),
                                               'rucher_id' => $rucher->getId())
                ));                 
            }               
        }       
        
        //Colonie vivante et tâche non liée à une visite
        if( !$tache->getColonie()->getMorte() && !$tache->getVisite() ){
            $menu->addChild('.icon-pencil Modifier', array(
                'route' => 'kg_beekeeping_management_update_tache',
                'routeParameters' => array('tache_id' => $tache->getId())
            ));  
        }
        
        // Tâche non liée à une visite
        if( !$tache->getVisite() ){
            $menu->addChild('.icon-trash Supprimer', array(
                'route' => 'kg_beekeeping_management_delete_tache',
                'routeParameters' => array('tache_id' => $tache->getId())
            ));  
        }

        return $menu;
    }
}