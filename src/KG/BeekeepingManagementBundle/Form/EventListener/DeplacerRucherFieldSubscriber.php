<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

class DeplacerRucherFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToEmplacement;
    private $exploitation;
    
    public function __construct($propertyPathToEmplacement, $exploitation)
    {
        $this->propertyPathToEmplacement = $propertyPathToEmplacement;
        $this->exploitation = $exploitation;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addRucherForm($form, $rucher = null)
    {       
        $exploitation = $this->exploitation;
        
        $formOptions = array(
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
        );
 
        if ($rucher) {
            $formOptions['data'] = $rucher;
        }
 
        $form->add('rucher', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $this->addRucherForm($form);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addRucherForm($form);
    }
}