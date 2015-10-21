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

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CauseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $colonie = $builder->getData();
        
        $lastDate = $colonie->getDateColonie();
        
        if( !$colonie->getVisites()->isEmpty() ){
            $date = $colonie->getVisites()->last()->getDate();
            if( $lastDate < $date ){
                $lastDate = $date;
            }
        }

        if( !$colonie->getTranshumances()->isEmpty() ){
            $date = $colonie->getTranshumances()->last()->getDate();
            if( $lastDate < $date ){
                $lastDate = $date;
            }
        }
        
        if( !$colonie->getRecoltes()->isEmpty() ){
            $date = $colonie->getRecoltes()->last()->getDate();
            if( $lastDate < $date ){
                $lastDate = $date;
            }
        }
        
        if( !$colonie->getRemerages()->isEmpty() ){
            $date = $colonie->getRemerages()->last()->getDate();
            if( $lastDate < $date ){
                $lastDate = $date;
            }
        }
        
        $startDate = date_add($lastDate,date_interval_create_from_date_string("1 days"));
        $startDateFormat = date_format($startDate,"Y-m-d"); 
        
        $builder    
            ->add('dateMort', 'collot_datetime', array( 
                    'pickerOptions' =>
                        array('format' => 'dd/mm/yyyy',
                            'autoclose' => true,
                            'startDate' => (string)$startDateFormat,
                            'endDate'   => date("Y-m-d"), 
                            'startView' => 'month',
                            'minView' => 'month',
                            'maxView' => 'month',
                            'todayBtn' => false,
                            'todayHighlight' => true,
                            'keyboardNavigation' => true,
                            'language' => 'fr',
                            'forceParse' => true,
                            'pickerReferer ' => 'default', 
                            'pickerPosition' => 'bottom-right',
                            'viewSelect' => 'month',
                            'initialDate' => date("Y-m-d"), 
                        ),
                    'read_only' => true,
                    'attr' => array(
                        'input_group' => array(
                            'prepend' => '.icon-calendar'
                        ))                      
                    ))               
            ->add('causes', 'entity', array(
                        'class'        => 'KGBeekeepingManagementBundle:Cause',
                        'choice_label' => 'libelle',
                        'multiple'     => true,
                        'required'     => false
                    ))
            ->add('autreCause', 'text', array('required' => false));
    }   
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Colonie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_colonnie';
    }
}
