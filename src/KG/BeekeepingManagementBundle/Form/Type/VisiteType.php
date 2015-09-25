<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\DateVisiteFieldSubscriber;

class VisiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $visites = $builder->getData()->getColonie()->getVisites();
        
        $startDate = new \DateTime();
        $startDate->setDate('2000', '01', '01');
        
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
                ->add('nbmiel')
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
                ->add('colonie', new ProductionType(), array(
                            'label'  => false,
                        ))
                ->addEventSubscriber(new DateVisiteFieldSubscriber($startDate));                
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
