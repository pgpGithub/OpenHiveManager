<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\DeplacerRucherFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\DeplacerEmplacementFieldSubscriber;

class DiviserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToEmplacement = 'emplacement';
        $exploitation = $builder->getData()->getExploitation()->getId();
        
        $builder
            ->addEventSubscriber(new DeplacerRucherFieldSubscriber($propertyPathToEmplacement, $exploitation))
            ->addEventSubscriber(new DeplacerEmplacementFieldSubscriber($propertyPathToEmplacement))    
            ->add('ruche', new RucheType())    
            ->add('nom')                              
            ->add('affectation', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Affectation',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ));
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
        return 'kg_beekeepingmanagementbundle_diviser';
    }
}
