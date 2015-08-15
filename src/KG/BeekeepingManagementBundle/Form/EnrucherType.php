<?php

namespace KG\BeekeepingManagementBundle\Form;

use KG\BeekeepingManagementBundle\Entity\Exploitation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use KG\BeekeepingManagementBundle\Entity\RucheRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\SecurityContext;

class EnrucherType extends AbstractType
{
    private $securityContext;
    private $exploitation;
    
    public function __construct(SecurityContext $securityContext, Exploitation $exploitation)
    {
        $this->securityContext = $securityContext;
        $this->exploitation = $exploitation;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $exploitation = $this->exploitation;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($exploitation) {
                $form = $event->getForm();

                $form->add('ruche', 'entity', 
                    array(
                        'class' => 'KG\BeekeepingManagementBundle\Entity\Ruche',
                        'property' => 'nom',
                        'query_builder' => function(RucheRepository $er) use ($exploitation) {
                                                return $er->getAvailableListByExploitation($exploitation);
                                            }
                    ));
        });    
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
