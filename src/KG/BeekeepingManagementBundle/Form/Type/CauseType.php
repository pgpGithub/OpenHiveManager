<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CauseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                             
            ->add('causes', 'entity', array(
                        'class'        => 'KGBeekeepingManagementBundle:Cause',
                        'choice_label' => 'libelle',
                        'multiple'     => true,
                        'required'     => false,
                        'attr' => array('label_col' => 4, 'widget_col' => 5)
                    ))
            ->add('autreCause', 'text', array('required' => false, 'attr' => array('label_col' => 4, 'widget_col' => 5)));
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
        return 'kg_beekeepingmanagementbundle_colonnie';
    }
}
