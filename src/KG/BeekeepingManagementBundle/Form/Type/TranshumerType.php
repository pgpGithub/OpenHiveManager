<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\TranshumerRucherFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\TranshumerEmplacementFieldSubscriber;

class TranshumerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToEmplacement = 'emplacement';
        
        $builder
            ->addEventSubscriber(new TranshumerRucherFieldSubscriber($propertyPathToEmplacement))
            ->addEventSubscriber(new TranshumerEmplacementFieldSubscriber($propertyPathToEmplacement));
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
        return 'kg_beekeepingmanagementbundle_transhumer';
    }
}
