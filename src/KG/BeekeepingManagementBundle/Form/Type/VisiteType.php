<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VisiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $visites = $builder->getData()->getColonnie()->getVisites();
        
        if($visites->last()){
            $startDate = date_add($visites->last()->getDate(),date_interval_create_from_date_string("1 days"));
            $startDate = date_format($startDate,"Y-m-d");           
        }
        else{
            $startDate = '2000-01-01';
        }
        
        $endDate   = date("Y-m-d");
        
        $builder
                ->add('activite', 'entity', array(
                            'class' => 'KGBeekeepingManagementBundle:Activite',
                            'choice_label' => 'libelle',
                            'empty_value' => '',
                            'empty_data'  => null
                        ))
                ->add('reine', 'checkbox', array(
                            'required'  => false,
                        ))
                ->add('essaimage', 'checkbox', array(
                            'required'  => false,
                        ))
                ->add('risqueessaimage', 'checkbox', array(
                            'required'  => false,
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
                            'required'  => false,
                        ))
                ->add('miel')
                ->add('pollen')
                ->add('propolis')
                ->add('gelee')
                ->add('observations', 'textarea', array(
                            'required'  => false,
                        ))
                ->add('colonnie', new ProductionType())
                ->add('date', 'collot_datetime', 
                    array( 
                            'pickerOptions' =>
                                array('format' => 'dd/mm/yyyy',
                                    'autoclose' => true,
                                    'startDate' => (string)$startDate,
                                    'endDate'   => (string)$endDate, 
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
                                ),
                            'read_only' => true
                ));                
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
