<?php

namespace KG\BeekeepingManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\RaceRepository;

class ColonnieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('race', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Race',
                        'property' => 'libelle'
                    ))
            ->add('affectation', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Affectation',
                        'property' => 'libelle'
                    ))
            ->add('provenanceColonnie', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Provenance',
                        'property' => 'libelle'
                    ))
            ->add('provenanceReine', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Provenance',
                        'property' => 'libelle'
                    ))                
            ->add('anneeReine')
            ->add('clippage')
            ->add('marquage', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Marquage',
                        'property' => 'libelle'
                    ));       
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Colonnie'
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
