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

namespace KG\BeekeepingManagementBundle\Form\Type;;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\DiviserNourritureFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\DiviserCouvainFieldSubscriber;
use KG\BeekeepingManagementBundle\Entity\Colonie;

class DiviserCorpsType extends AbstractType
{
    private $colonieMere;
    
    /**
     * Constructor
     */
    public function __construct(\KG\BeekeepingManagementBundle\Entity\Colonie $colonieMere)
    {
        $this->colonieMere = $colonieMere;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:TypeRuche',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))      
            ->addEventSubscriber(new DiviserNourritureFieldSubscriber($this->colonieMere->getRuche()->getCorps()->getNbnourriture()))
            ->addEventSubscriber(new DiviserCouvainFieldSubscriber($this->colonieMere->getRuche()->getCorps()->getNbcouvain()));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Corps'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_corps';
    }
}
