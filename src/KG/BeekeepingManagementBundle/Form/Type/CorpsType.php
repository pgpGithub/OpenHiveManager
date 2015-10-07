<?php

namespace KG\BeekeepingManagementBundle\Form\Type;;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\NbCadresFieldSubscriber;

class CorpsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:TypeRuche',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null,
                        'attr' => array('label_col' => 4, 'widget_col' => 5)
                    ))                 
            ->addEventSubscriber(new NbCadresFieldSubscriber())
            ->add('nbnourriture', 'integer', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
            ->add('nbcouvain', 'integer', array('attr' => array('label_col' => 4, 'widget_col' => 5)));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Corps'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_corps';
    }
}
