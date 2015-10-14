<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
 
class TypeRemerageFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addTypeRemerageForm($form, $disabled)
    {
        $form->add('naturel', 'checkbox', array(
            'label' => false,
            'required'  => false,
            'disabled'  => $disabled
        ));
    }
 
    public function preSetData(FormEvent $event)
    {
        $this->addTypeRemerageForm($event->getForm(), $event->getData()->getNaturel());
    }
 
    public function preSubmit(FormEvent $event)
    {


    }
}