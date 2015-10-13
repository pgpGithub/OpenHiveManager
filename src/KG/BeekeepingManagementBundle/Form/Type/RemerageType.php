<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RemerageType extends AbstractType
{
    private $date;
    
    /**
     * Constructor
     */
    public function __construct(\DateTime $date)
    {
        $this->date = $date;    
    }    
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $startDate = date_add($this->date,date_interval_create_from_date_string("1 days"));
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
                        'read_only' => true
                        ))
                ->add('naturel', 'checkbox', array(
                            'label' => false,
                            'required'  => false
                        ))
                ->add('reine', new ReineType(), array(
                            'label' => false,
                        ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Remerage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_remerage';
    }
}
