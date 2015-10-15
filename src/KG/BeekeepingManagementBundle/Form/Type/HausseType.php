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
use KG\BeekeepingManagementBundle\Entity\HausseVisite;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HausseType extends AbstractType
{   
    private $visite;

    /**
     * Constructor
     */
    public function __construct($visite)
    {
        $this->visite = $visite;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nbcadres', 'integer', array('label' => 'Nombre de cadres', 'attr' => array('label_col' => 5, 'widget_col' => 7)))
                ->add('nbplein', 'integer', array('label' => 'Nombre de cadres plein', 'attr' => array('label_col' => 5, 'widget_col' => 7)));  
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\HausseVisite',
            'empty_data' => new HausseVisite($this->visite),
        ));
    } 

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_hausse';
    }
}
