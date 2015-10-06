<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use KG\BeekeepingManagementBundle\Entity\HausseVisite;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HausseType extends AbstractType
{   
    private $visite;

    /**
     * Constructor
     */
    public function __construct($visite)
    {
        $this->visite = $visite;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nbplein');               
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\HausseVisite',
            'empty_data' => new HausseVisite($this->visite),
        ));
    } 

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_hausse';
    }
}
