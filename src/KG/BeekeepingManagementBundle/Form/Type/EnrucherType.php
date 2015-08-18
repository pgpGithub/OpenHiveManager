<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\AddTypeRucheFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\AddRucheFieldSubscriber;

class EnrucherType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToRuche = 'ruche';
        
        $builder
            ->addEventSubscriber(new AddRucheFieldSubscriber($propertyPathToRuche))
            ->addEventSubscriber(new AddTypeRucheFieldSubscriber($propertyPathToRuche));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {       
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_enrucher';
    }
}
