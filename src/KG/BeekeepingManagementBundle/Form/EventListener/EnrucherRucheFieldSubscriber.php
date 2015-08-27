<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
 
class EnrucherRucheFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToRuche;
 
    public function __construct($propertyPathToRuche)
    {
        $this->propertyPathToRuche = $propertyPathToRuche;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addRucheForm($form, $type)
    {
        $formOptions = array(
            'class'         => 'KGBeekeepingManagementBundle:Ruche',
            'choice_label'  => 'nom',
            'empty_value'   => '',
            'attr'          => array(
                'class' => 'ruche_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($type) {
                $qb = $repository->createQueryBuilder('ruche')
                    ->innerJoin('ruche.type', 'type')
                    ->where('type.id = :type')
                    ->setParameter('type', $type)
                ;
 
                return $qb;
            }
        );
 
        $form->add($this->propertyPathToRuche, 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::createPropertyAccessor();
 
        $ruche = $accessor->getValue($data, $this->propertyPathToRuche);
        $type  = ($ruche) ? $ruche->getType()->getId() : null;
 
        $this->addRucheForm($form, $type);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $type = array_key_exists('type', $data) ? $data['type'] : null;
 
        $this->addRucheForm($form, $type);
    }
}