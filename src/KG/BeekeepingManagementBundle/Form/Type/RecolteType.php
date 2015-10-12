<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                    'read_only' => true
            ))
            ->add('typemiel');
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
