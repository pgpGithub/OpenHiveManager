<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
class ColonieFilleType extends AbstractType
{
    
    private $datemin;
    
    /**
     * Constructor
     */
    public function __construct($datemin)
    {
        $this->datemin = $datemin;
    }
    
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
                        'attr' => array('label_col' => 4, 'widget_col' => 5)
            ))                                  
            ->add('affectation', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Affectation',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null,
                        'attr' => array('label_col' => 4, 'widget_col' => 5)
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
