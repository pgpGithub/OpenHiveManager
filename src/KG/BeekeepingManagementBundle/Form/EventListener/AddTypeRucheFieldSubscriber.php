<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
 
class AddTypeRucheFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToRuche;
 
    public function __construct($propertyPathToRuche)
    {
        $this->propertyPathToRuche = $propertyPathToRuche;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addTypeForm($form, $type = null)
    {
        $formOptions = array(
            'class'         => 'KGBeekeepingManagementBundle:TypeRuche',
            'choice_label'  => 'libelle',
            'empty_value'   => '',
            'mapped'        => false,
            'attr'          => array(
                'class' => 'type_selector',
            ),
        );
 
        if ($type) {
            $formOptions['data'] = $type;
        }
 
        $form->add('type', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::createPropertyAccessor();
 
        $ruche    = $accessor->getValue($data, $this->propertyPathToRuche);
        $type = ($ruche) ? $ruche->getType() : null;
 
        $this->addTypeForm($form, $type);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addTypeForm($form);
    }
}