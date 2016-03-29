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
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\VAxis;

class Chart
{
    private $chartData;


    public function __construct(ChartData $chartData)
    {
        $this->chartData = $chartData;
    }


    /**
     * Crée le graphique du montant des bénéfices par jour.
     *
     * @return LineChart
     */
    public function getChartPoidsParVisite( Ruche $ruche )
    {

        $line = new LineChart();
        $line->getData()->setArrayToDataTable($this->chartData->getChartPoidsParVisite( $ruche ));
        
        $vAxis = new VAxis();
        $vAxis->setTitle('Poids en Kg');    

        $line->getOptions()->setVAxes([$vAxis]); 
        
        $line->getOptions()->getHAxis()->setFormat('dd/MM/yy');

        $line->getOptions()->setLineWidth(2);
        $line->getOptions()->setHeight(500);
        $line->getOptions()->getLegend()->setPosition('none');        
        
        return $line;
    }  
}