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
 
    private function addNbCadresForm($form, $type)
    {              
        $formOptions = array(
            'class'         => 'KGBeekeepingManagementBundle:SousTypeRuche',
            'choice_label'  => 'nbcadres',
            'empty_value'   => '',
            'mapped'        => false,
            'attr'          => array(
                'class' => 'nbcadres_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($type) {
                $qb = $repository->queryfindByTypeId($type);
                return $qb;
            }
        );
 
        $form->add('nbcadres', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::createPropertyAccessor();
 
        $ruche = $accessor->getValue($data, 'ruche');
        $type = ($ruche) ? $ruche->getType()->getId() : null;
        $this->addNbCadresForm($form, $type);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $this->addNbCadresForm($form);
    }
}