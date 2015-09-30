<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecolteRucherType extends AbstractType
{
    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /**
     * Constructor
     * 
     * @param Doctrine $doctrine
     */
    public function __construct($manager)
    {
        $this->em = $manager;
    }    
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $recolte = $builder->getData();
        $recolte->setDate(new \DateTime());
        
        $rucher  = $recolte->getRucher();
        
        $recoltes = $rucher->getRecoltesrucher();
        
        $startDate = new \DateTime();
        $startDate->setDate('2000', '01', '01');
        
        if($recoltes->last()){
            $startDate = $recoltes->last()->getDate();
        }
        
        $startDateFormat = date_format($startDate,"Y-m-d"); 
        
        $builder
            ->add('ruches', 'entity', array(
                        'class'    => 'KGBeekeepingManagementBundle:Ruche',
                        'choice_label' => 'nom',
                        'choices'  => $this->getArrayOfEntities($rucher),
                        'mapped'   => false,
                        'expanded' => true,
                        'multiple' => true,
             ))
            ->add('date', 'collot_datetime', array( 
                    'pickerOptions' =>
                        array('format' => 'dd/mm/yyyy',
                            'autoclose' => true,
                            'startDate' => (string)$startDateFormat,
                            'endDate'   => date("Y-m-d"), 
                            'startView' => 'month',
                            'minView' => 'month',
                            'maxView' => 'month',
                            'todayBtn' => false,
                            'todayHighlight' => true,
                            'keyboardNavigation' => true,
                            'language' => 'fr',
                            'forceParse' => true,
                            'pickerReferer ' => 'default', 
                            'pickerPosition' => 'bottom-right',
                            'viewSelect' => 'month',
                            'initialDate' => date("Y-m-d"), 
                        ),
                    'read_only' => true
            ));                
    }

    private function getArrayOfEntities(\KG\BeekeepingManagementBundle\Entity\Rucher $rucher){
        $repo = $this->em->getRepository('KGBeekeepingManagementBundle:Ruche');
        $ruches = $repo->getRucheByRucher($rucher->getId());
        $ruches_with_hausse = new \Doctrine\Common\Collections\ArrayCollection();
                
        foreach( $ruches as $ruche ){
            if( !$ruche->getHausses()->isEmpty() ){
                $ruches_with_hausse->add($ruche); 
            }
        }
        return $ruches_with_hausse;
    } 

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\RecolteRucher'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_recolterucher';
    }
}
