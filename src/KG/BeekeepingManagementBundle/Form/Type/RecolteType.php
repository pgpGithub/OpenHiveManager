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
use KG\BeekeepingManagementBundle\Form\EventListener\RecolteFieldSubscriber;
use KG\BeekeepingManagementBundle\Validator\CheckRecolte;

class RecolteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {              
        $startDate = new \DateTime();
        
        $colonie = $builder->getData()->getColonie();
        
        $recoltes  = $colonie->getRecoltes();
        if($recoltes->last()){
            $startDate = $recoltes->last()->getDate();
        }else{
            $startDate = $colonie->getVisites()->last()->getDate();
        }
        
        $startDateFormat = date_format($startDate,"Y-m-d"); 
        
        $builder
            ->add('date', 'collot_datetime', array( 
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
            ->add('typemiel')
            ->add('nbcadres', 'hidden');
        
        $hausses = $builder->getData()->getColonie()->getRuche()->getHausses();
        
        foreach( $hausses as $hausse ){
            $fieldname = 'hausse_' . $hausse->getId();  
            $builder->add($fieldname, 'integer', array(
                        'mapped'      => false,
                        'constraints' => new CheckRecolte($hausse->getNbplein())
                    ));           
        }
        
        $builder->addEventSubscriber(new RecolteFieldSubscriber($hausses));   
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Recolte'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_recolte';
    }
}
