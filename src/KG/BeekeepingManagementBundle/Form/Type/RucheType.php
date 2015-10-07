<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RucheType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',  'text', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
            ->add('matiere', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Matiere',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null,
                        'attr' => array('label_col' => 4, 'widget_col' => 5)
                    ))
            ->add('corps', new CorpsType())
            ->add('image', new ImageType(), array('required' => false));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Ruche'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_ruche';
    }
}
