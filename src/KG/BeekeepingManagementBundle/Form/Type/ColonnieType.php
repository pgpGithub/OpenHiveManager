<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\RaceRepository;

class ColonnieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
                
            ->add('race', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Race',
                        'property' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('anneeColonnie', 'collot_datetime', 
                    array( 
                            'pickerOptions' =>
                                array('format' => 'yyyy',
                                    'autoclose' => true,
                                    'startDate' => '1950',
                                    'endDate' => date('Y'), 
                                    'startView' => 'decade',
                                    'minView' => 'decade',
                                    'maxView' => 'decade',
                                    'todayBtn' => false,
                                    'todayHighlight' => false,
                                    'keyboardNavigation' => true,
                                    'language' => 'fr',
                                    'forceParse' => true,
                                    'pickerReferer ' => 'default', 
                                    'pickerPosition' => 'bottom-right',
                                    'viewSelect' => 'decade',
                                    'initialDate' => date('Y'), 
                                ),
                            'read_only' => true
                ))
                
            ->add('affectation', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Affectation',
                        'property' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('provenanceColonnie', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Provenance',
                        'property' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('etat', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Etat',
                        'property' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('agressivite', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Agressivite',
                        'property' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))
                
            ->add('provenanceReine', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Provenance',
                        'property' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))     
                
            ->add('anneeReine', 'collot_datetime', 
                    array( 
                            'pickerOptions' =>
                                array('format' => 'yyyy',
                                    'autoclose' => true,
                                    'startDate' => '1950',
                                    'endDate' => date('Y'), 
                                    'startView' => 'decade',
                                    'minView' => 'decade',
                                    'maxView' => 'decade',
                                    'todayBtn' => false,
                                    'todayHighlight' => false,
                                    'keyboardNavigation' => true,
                                    'language' => 'fr',
                                    'forceParse' => true,
                                    'pickerReferer ' => 'default', 
                                    'pickerPosition' => 'bottom-right',
                                    'viewSelect' => 'decade',
                                    'initialDate' => date('Y'), 
                                ),
                            'read_only' => true
                ))
                
            ->add('clippage', 'checkbox', array(
                'required'  => false,
            ))
        
            ->add('marquage', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Marquage',
                        'property' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ));       
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Colonnie'
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
