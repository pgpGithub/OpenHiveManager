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

namespace KG\BeekeepingManagementBundle\Chart;
use KG\BeekeepingManagementBundle\Entity\Ruche;

class ChartData
{    

    private $security;


    public function __construct($security)
    {
        $this->security = $security;
    }
        
    static private function cmp($a, $b)
    {
        if ($a['Date'] == $b['Date']) {
            return 0;
        }
        return ($a['Date'] < $b['Date']) ? -1 : 1;
    }    
    
    private function getPoidsParVisite( Ruche $ruche )
    {
        $visites = $ruche->getColonie()->getVisites();    
        
        $tabVisites = array();
                
        foreach( $visites as $visite )
        {
            $tabVisites[] = array('Date' => $visite->getDate(), 'Poids' => 0 + $visite->getPoids() );                
        }
                
        // Obtient une liste de colonnes
        foreach ($tabVisites as $key => $row) {
            $date[$key]  = $row['Date'];
        }

        if( $tabVisites )
        {
            array_multisort($date, SORT_ASC, $tabVisites);   
        }
        
        return $tabVisites;
    }

    public function getChartPoidsParVisite( Ruche $ruche )
    {
        $tabPoids = $this->getPoidsParVisite( $ruche );     

        $tab[] = ['Date', 'Poids'];
        
        foreach( $tabPoids as $key => $linePoids )
        {
            $tab[] = [$linePoids['Date'],$linePoids['Poids']];
        }
        
        if( !$tabPoids )
        {
            $tab[] = [new \DateTime(),0];
        }
        
        return $tab;
    }
}
