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
                            'empty_data'  => null,
                            'attr' => array('label_col' => 4, 'widget_col' => 5)
                        ))                
                ->add('reine', 'checkbox', array(
                            'label' => false,
                            'required'  => false
                        ))
                ->add('pollen', 'checkbox', array(
                            'label' => false,
                            'required'  => false
                        ))
                ->add('nbcouvain', 'integer', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
                ->add('nbnourriture', 'integer', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
                ->add('celroyales', 'checkbox', array(
                            'label' => false,
                            'required'  => false
                        ))
                ->add('etat', 'entity', array(
                            'class' => 'KGBeekeepingManagementBundle:Etat',
                            'choice_label' => 'libelle',
                            'empty_value' => '',
                            'empty_data'  => null,
                            'attr' => array('label_col' => 4, 'widget_col' => 5)
                        ))
                ->add('agressivite', 'entity', array(
                            'class' => 'KGBeekeepingManagementBundle:Agressivite',
                            'choice_label' => 'libelle',
                            'empty_value' => '',
                            'empty_data'  => null,
                            'attr' => array('label_col' => 4, 'widget_col' => 5)
                        ))
                ->add('nourrissement', 'textarea', array(
                            'required'  => false,
                            'attr' => array('label_col' => 4, 'widget_col' => 5)
                        ))
                ->add('traitement', 'textarea', array(
                            'required'  => false,
                            'attr' => array('label_col' => 4, 'widget_col' => 5)
                        ))
                ->add('observations', 'textarea', array(
                            'required'  => false,
                            'attr' => array('label_col' => 4, 'widget_col' => 5)
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
                        'attr' => array('label_col' => 4, 'widget_col' => 5),
                        'read_only' => true
                        ))
                ->add('hausses', 'bootstrap_collection', array(
                    'type' => new HausseType($builder->getData()), 
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text'    => '.icon-plus Ajouter hausse',
                    'delete_button_text' => '.icon-trash',
                    'sub_widget_col'     => 10,
                    'button_col'         => 1,
                    'attr' => array('label_col' => 4, 'widget_col' => 5),
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
