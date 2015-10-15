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

class ColonieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                               
            ->add('dateColonie', 'collot_datetime', 
                    array( 
                            'pickerOptions' =>
                                array('format' => 'mm/yyyy',
                                    'autoclose' => true,
                                    'startDate' => '1950',
                                    'endDate' => date('Y-m-d'), 
                                    'startView' => 'decade',
                                    'minView' => 'year',
                                    'maxView' => 'decade',
                                    'todayBtn' => false,
                                    'todayHighlight' => false,
                                    'keyboardNavigation' => true,
                                    'language' => 'fr',
                                    'forceParse' => true,
                                    'pickerReferer ' => 'default', 
                                    'pickerPosition' => 'bottom-right',
                                    'viewSelect' => 'year',
                                    'initialDate' => date('Y-m-d'), 
                                ),
                            'read_only' => true,
                            'attr' => array(
                                'input_group' => array(
                                    'prepend' => '.icon-calendar'
                                ))                          
                ))
                
            ->add('affectation', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Affectation',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('origineColonie', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Origine',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('etat', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Etat',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('agressivite', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Agressivite',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('remerages', 'collection', array(
                'type'  => new FirstRemerageType(),
                'label' => false
            ));                       
   
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
        return 'kg_beekeepingmanagementbundle_colonie';
    }
}
