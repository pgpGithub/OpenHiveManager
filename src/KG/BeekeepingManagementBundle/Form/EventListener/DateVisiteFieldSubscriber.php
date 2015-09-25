<?php

namespace KG\BeekeepingManagementBundle\Form\EventListener;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;


class DateVisiteFieldSubscriber implements EventSubscriberInterface
{
    private $startDate;
    private $endDate;
    
    /**
     * Constructor
     */
    public function __construct($startDate)
    {
        $this->startDate = $startDate;
        $this->endDate = date("Y-m-d");
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addDateVisiteForm($form)
    {       
        $startDateFormat = date_format($this->startDate,"Y-m-d"); 
        $form->add('date', 'collot_datetime', 
                array( 
                        'pickerOptions' =>
                            array('format' => 'dd/mm/yyyy',
                                'autoclose' => true,
                                'startDate' => (string)$startDateFormat,
                                'endDate'   => (string)$this->endDate, 
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
                                'initialDate' => (string)$this->endDate, 
                            ),
                        //'read_only' => true
            )); 
    }
 
    public function preSetData(FormEvent $event)
    {;
        $form = $event->getForm();
        $this->addDateVisiteForm($form);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $dateColonieFille = array_key_exists('date', $data) ? $data['date'] : null;
        $date = new \DateTime();
        $date->setDate(substr($dateColonieFille, 6, 4), substr($dateColonieFille, 3, 2), substr($dateColonieFille, 0, 2));
        $date->setTime('00', '00', '00');
        
        if( $date <= $this->startDate){
            $form->addError(new FormError('La date ne peut pas être antérieur ou égale à celle de la dernière visite'));
        }
        
        $this->addDateVisiteForm($form);
    }
}