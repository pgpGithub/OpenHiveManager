<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
 
class TranshumerEmplacementFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToEmplacement;
 
    public function __construct($propertyPathToEmplacement)
    {
        $this->propertyPathToEmplacement = $propertyPathToEmplacement;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addEmplacementForm($form, $rucher)
    {
        $formOptions = array(
            'class'         => 'KGBeekeepingManagementBundle:Emplacement',
            'choice_label'  => 'nom',
            'empty_value'   => '',
            'attr'          => array(
                'class' => 'emplacement_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($rucher) {
                $qb = $repository->queryfindByRucherId($rucher);
                return $qb;
            }
        );
 
        $form->add($this->propertyPathToEmplacement, 'entity', $formOptions);
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
        $rucher  = ($emplacement) ? $emplacement->getRucher()->getId() : null;
 
        $this->addEmplacementForm($form, $rucher);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $rucher = array_key_exists('rucher', $data) ? $data['rucher'] : null;
 
        $this->addEmplacementForm($form, $rucher);
    }
}