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
            ->add('rucher', 'entity', array(
                        'class'         => 'KGBeekeepingManagementBundle:Rucher',
                        'choice_label'  => 'nom',
                        'empty_value'   => '',
                        'mapped'        => false,
                        'attr'          => array(
                            'class' => 'rucher_selector',
                        ),
                        'query_builder' => function (EntityRepository $repository) use ($exploitation) {
                            $qb = $repository->queryfindByExploitationId($exploitation);
                            return $qb;
                        }
                    ))
            //->addEventSubscriber(new DeplacerRucherFieldSubscriber($propertyPathToEmplacement, $exploitation))
            ->addEventSubscriber(new DeplacerEmplacementFieldSubscriber($propertyPathToEmplacement))    
            ->add('ruche', new DadantType())    
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
