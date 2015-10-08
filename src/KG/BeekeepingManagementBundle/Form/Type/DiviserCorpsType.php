<?php

namespace KG\BeekeepingManagementBundle\Form\Type;;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Form\EventListener\NbCadresFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\DiviserNourritureFieldSubscriber;
use KG\BeekeepingManagementBundle\Form\EventListener\DiviserCouvainFieldSubscriber;

class DiviserCorpsType extends AbstractType
{
    private $colonieMere;
    
    /**
     * Constructor
     */
    public function __construct(\KG\BeekeepingManagementBundle\Entity\Colonie $colonieMere)
    {
        $this->colonieMere = $colonieMere;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'entity', array(
                        'class' => 'KGBeekeepingManagementBundle:TypeRuche',
                        'choice_label' => 'libelle',
                        'empty_value' => '',
                        'empty_data'  => null
                    ))   
            ->addEventSubscriber(new NbCadresFieldSubscriber())    
            ->addEventSubscriber(new DiviserNourritureFieldSubscriber($this->colonieMere->getRuche()->getCorps()->getNbnourriture()))
            ->addEventSubscriber(new DiviserCouvainFieldSubscriber($this->colonieMere->getRuche()->getCorps()->getNbcouvain()));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Corps'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_corps';
    }
}
