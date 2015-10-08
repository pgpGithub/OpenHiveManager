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
        $builder->add('nbplein', 'integer', array('label' => 'Cadres plein :', 'attr' => array('label_col' => 5, 'widget_col' => 7)));  
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
