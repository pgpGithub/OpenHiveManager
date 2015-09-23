<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\DeplacerEmplacementFieldSubscriber;
use Doctrine\ORM\EntityRepository;

class DiviserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToEmplacement = 'emplacement';
        $exploitation = $builder->getData()->getColonie()->getExploitation()->getId();
        
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
            ->addEventSubscriber(new DeplacerEmplacementFieldSubscriber($propertyPathToEmplacement))    
            ->add('nom',  'text')   
            ->add('matiere', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:Matiere',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))     
            ->add('corps', new CorpsType())
            ->add('image', new ImageType(), array('required' => false))
            ->add('colonie', new ColonieFilleType());
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
        return 'kg_beekeepingmanagementbundle_diviser';
    }
}
