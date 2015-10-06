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
                            'required'  => false,
                        ))
                ->add('pollen', 'checkbox', array(
                            'required'  => false,
                        ))
                ->add('nbcouvain')
                ->add('nbnourriture')
                ->add('celroyales', 'checkbox', array(
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
                ->add('observations', 'textarea', array(
                            'required'  => false,
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
                        'read_only' => true
                        ))
                ->add('hausses', 'collection', array(
                    'type' => new HausseType($builder->getData()),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => false
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
