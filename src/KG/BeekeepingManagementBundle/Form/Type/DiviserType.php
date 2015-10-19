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
use Doctrine\ORM\EntityRepository;

class DiviserType extends AbstractType
{
    
    private $datemin;
    
    /**
     * Constructor
     */
    public function __construct(\DateTime $datemin)
    {
        $this->datemin = $datemin;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $exploitation = $builder->getData()->getRemerages()->last()->getReine()->getReineMere()->getRemerage()->getColonie()->getRucher()->getExploitation();
        
        $builder                       
            ->add('dateColonie', 'collot_datetime', 
                array( 
                        'pickerOptions' =>
                            array(
                                'format' => 'mm/yyyy',
                                'autoclose' => true,
                                'startDate' => date_format($this->datemin,"Y-m-d"),
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
            ->add('remerages', 'collection', array(
                        'type'  => new DiviserRemerageType(),
                        'label' => false
                    ))       
            ->add('ruche', new DiviserRucheType($exploitation)); 
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
