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


class VisiteType extends AbstractType
{

    /** @var \Doctrine\ORM\EntityManager */	 
    private $em;

    /**	 
     * Constructor	 
     * 	 
     * @param Doctrine $doctrine	 
     */	 
    public function __construct($manager) 
    {	 
        $this->em = $manager;	 
    }    
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {         
        $colonie = $builder->getData()->getColonie();
        $visites = $colonie->getVisites();
        $startDate = $colonie->getDateColonie();
        
        if($visites->last()){
            if($visites->last()->getId() == $builder->getData()->getId()){
                $len = count($visites) - 2;
                if($visites{$len}){
                    $startDate = date_add($visites{$len}->getDate(),date_interval_create_from_date_string("1 days"));
                }
            }
            else{
                $startDate = date_add($visites->last()->getDate(),date_interval_create_from_date_string("1 days"));
            }
        }
        
        $startDateFormat = date_format($startDate,"Y-m-d"); 

        $builder
                ->add('activite', 'entity', array(
                            'class' => 'KGBeekeepingManagementBundle:Activite',
                            'choice_label' => 'libelle',
                            'empty_value' => '',
                            'empty_data'  => null
                        ))                
                ->add('reine', 'checkbox', array(
                            'label' => false,
                            'required'  => false
                        ))
                ->add('pollen', 'checkbox', array(
                            'label' => false,
                            'required'  => false
                        ))
                ->add('nbcouvain', 'integer')
                ->add('nbnourriture', 'integer')
                ->add('poids', 'number', array('precision' => 3))
                ->add('celroyales', 'checkbox', array(
                            'label' => false,
                            'required'  => false
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
                ->add('nourrissement', 'textarea', array(
                            'required'  => false,
                        ))
                ->add('traitement', 'textarea', array(
                            'required'  => false
                        ))
                ->add('observations', 'textarea', array(
                            'required'  => false
                        ))
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
                ->add('hausses', 'collection', array(
                    'type' => new HausseType($builder->getData()), 
                    'allow_add' => true,
                    'allow_delete' => true
                    ))                    
                
                ->add('taches', 'entity', array(	 
                    'class'        => 'KGBeekeepingManagementBundle:Tache',
                    'choice_label' => null,
                    'choices'      => $this->getArrayOfEntities($colonie, $builder->getData()),
                    'mapped'       => false,
                    'expanded'     => true,
                    'multiple'     => true,
                    'label'        => false
                ));               
    }

    private function getArrayOfEntities(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie, \KG\BeekeepingManagementBundle\Entity\Visite $visite)
    {
        $repo = $this->em->getRepository('KGBeekeepingManagementBundle:Tache');
        return $repo->getListByColonie($colonie, $visite)->getResult();
    } 
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Visite'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_visite';
    }
}
