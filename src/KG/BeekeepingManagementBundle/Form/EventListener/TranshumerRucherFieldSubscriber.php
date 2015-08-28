<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
 
class TranshumerRucherFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToEmplacement;
 
    public function __construct($propertyPathToEmplacement)
    {
        $this->propertyPathToEmplacement = $propertyPathToEmplacement;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addRucherForm($form, $type = null)
    {
        $formOptions = array(
            'class'         => 'KGBeekeepingManagementBundle:Rucher',
            'choice_label'  => 'nom',
            'empty_value'   => '',
            'mapped'        => false,
            'attr'          => array(
                'class' => 'rucher_selector',
            ),
        );
 
        if ($type) {
            $formOptions['data'] = $rucher;
        }
 
        $form->add('rucher', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::createPropertyAccessor();
 
        $emplacement = $accessor->getValue($data, $this->propertyPathToEmplacement);
        $rucher = ($emplacement) ? $emplacement->getRucher() : null;
 
        $this->addRucherForm($form, $rucher);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addRucherForm($form);
    }
}