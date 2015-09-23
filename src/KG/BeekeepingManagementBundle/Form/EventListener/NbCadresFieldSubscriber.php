<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

class NbCadresFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addSousTypeForm($form, $type = null)
    {              
        $formOptions = array(
            'class'         => 'KGBeekeepingManagementBundle:SousTypeRuche',
            'choice_label'  => 'nbcadres',
            'empty_value'   => '',
            'attr'          => array(
                'class' => 'soustype_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($type) {
                $qb = $repository->queryfindByTypeId($type);
                return $qb;
            }
        );
        
        if ($type) {
            $formOptions['data'] = $type;
        }        
 
        $form->add('soustype', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::createPropertyAccessor();
 
        $soustype = $accessor->getValue($data, 'soustype');
        $type= ($soustype) ? $soustype->getType() : null;
 
        $this->addSousTypeForm($form, $type);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $type = array_key_exists('type', $data) ? $data['type'] : null;
        $this->addSousTypeForm($form, $type);
    }
}