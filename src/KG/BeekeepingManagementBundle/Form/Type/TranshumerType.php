<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\TranshumerRucherFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\TranshumerEmplacementFieldSubscriber;
use Doctrine\ORM\EntityRepository;

class TranshumerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToEmplacement = 'emplacement';
        $exploitation = $builder->getData()->getEmplacement()->getRucher()->getExploitation()->getId();
        
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
