<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use KG\BeekeepingManagementBundle\Form\EventListener\DeplacerEmplacementFieldSubscriber;

class TranshumanceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToEmplacement = 'emplacementto';
        
        $transhumance = $builder->getData();
        $colonie      = $transhumance->getColonie();
        $exploitation = $colonie->getRucher()->getExploitation()->getId();
        $rucherfrom = $transhumance->getEmplacementfrom()->getRucher()->getId();
        
        $transhumance->setDate(new \DateTime());
        
        $transhumances = $colonie->getTranshumances();
        
        $startDate = $colonie->getDateColonie();
        
        if($transhumances->last()){
            $startDate = $transhumances->last()->getDate();
        }
        
        $startDateFormat = date_format($startDate,"Y-m-d"); 
        
        $builder
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
            ))    
            ->add('rucher', 'entity', array(
                    'class'         => 'KGBeekeepingManagementBundle:Rucher',
                    'choice_label'  => 'nom',
                    'empty_value'   => '',
                    'mapped'        => false,
                    'attr'          => array(
                        'class' => 'rucher_selector',
                    ),
                    'query_builder' => function (EntityRepository $repository) use ($exploitation, $rucherfrom) {
                        $qb = $repository->queryfindByExploitationId($exploitation, $rucherfrom);
                        return $qb;
                    }
                ))
            ->addEventSubscriber(new DeplacerEmplacementFieldSubscriber($propertyPathToEmplacement))  ;  
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Transhumance'
        ));        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_transhumance';
    }
}
