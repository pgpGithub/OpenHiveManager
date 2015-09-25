<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;


class DateColonieFilleFieldSubscriber implements EventSubscriberInterface
{
    private $datemin;
    
    /**
     * Constructor
     */
    public function __construct($datemin)
    {
        $this->datemin = $datemin;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addColonieFilleForm($form)
    {       
        $form->add('dateColonie', 'collot_datetime', 
                    array( 
                            'pickerOptions' =>
                                array(
                                    'format' => 'mm/yyyy',
                                    'autoclose' => true,
                                    'startDate' => date_format($this->datemin,"Y-m-d"),
                                    'endDate' => date('Y-m-d'), 
                                    'startView' => 'decade',
                                    'minView' => 'year',
                                    'maxView' => 'decade',
                                    'todayBtn' => false,
                                    'todayHighlight' => false,
                                    'keyboardNavigation' => true,
                                    'language' => 'fr',
                                    'forceParse' => true,
                                    'pickerReferer ' => 'default', 
                                    'pickerPosition' => 'bottom-right',
                                    'viewSelect' => 'year',
                                    'initialDate' => date('Y-m-d'), 
                                ),
                            //'read_only' => true
                ));
    }
 
    public function preSetData(FormEvent $event)
    {;
        $form = $event->getForm();
        $this->addColonieFilleForm($form);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $dateColonieFille = array_key_exists('dateColonie', $data) ? $data['dateColonie'] : null;
        $date = new \DateTime();
        $date->setDate(substr($dateColonieFille, 3, 7), substr($dateColonieFille, 0, 2), '01');

        if( $date < $this->datemin){
            $form->addError(new FormError('La date de division ne peut pas être antérieur à la date de naissance de la colonie mère'));
        }
        
        $this->addColonieFilleForm($form);
    }
}