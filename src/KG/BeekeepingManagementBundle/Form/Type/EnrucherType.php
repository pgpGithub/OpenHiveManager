<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\EnrucherTypeRucheFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\EnrucherRucheFieldSubscriber;

class EnrucherType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToRuche = 'ruche';
        $exploitation = $builder->getData()->getExploitation()->getId();
        
        $builder
            ->addEventSubscriber(new EnrucherTypeRucheFieldSubscriber($propertyPathToRuche))
            ->addEventSubscriber(new EnrucherRucheFieldSubscriber($propertyPathToRuche, $exploitation));
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
