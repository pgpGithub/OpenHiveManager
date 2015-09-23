<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColonieFilleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('appellation')
                               
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
                            'read_only' => true
                ))
                
            ->add('affectation', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Affectation',
                        'choice_label' => 'libelle',
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
