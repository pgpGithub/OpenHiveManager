<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProprietaireType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
            ->add('prenom', 'text', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
            ->add('adresse', 'text', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
            ->add('telephone', 'text', array('attr' => array('label_col' => 4, 'widget_col' => 5)))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Proprietaire'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_proprietaire';
    }
}
