<?php

/* 
 * Copyright (C) 2015 Kévin Grenèche < kevin.greneche at openhivemanager.org >
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace KG\BeekeepingManagementBundle\Form\EventListener;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
 
class TypeRemerageFieldSubscriber implements EventSubscriberInterface
{  
    private $race;
    
    /**
     * Constructor
     */
    public function __construct($race)
    {
        $this->race = $race;   
    }    
    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }    
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        if (null === $data) {
            return;
        }
        
        //$accessor = PropertyAccess::createPropertyAccessor();
        //$naturel = $accessor->getValue($data, 'naturel');
        $naturel = $form->get('naturel')->getData();
        
        if($naturel){
            $form->get('reine')->add('race', 'entity', array(
                                        'class' => 'KGBeekeepingManagementBundle:Race',
                                        'choice_label' => 'libelle',
                                        'empty_value' => '',
                                        'empty_data'  => null, 
                                        'attr' => array(
                                            'style' => 'display:none;'
                                            )
                                        )) 
                                ->add('anneeReine', 'collot_datetime', 
                                array( 
                                        'pickerOptions' =>
                                            array('format' => 'yyyy',
                                                'autoclose' => true,
                                                'endDate'   => date('Y'), 
                                                'startView' => 'decade',
                                                'minView' => 'decade',
                                                'maxView' => 'decade',
                                                'todayBtn' => false,
                                                'todayHighlight' => false,
                                                'keyboardNavigation' => true,
                                                'language' => 'fr',
                                                'forceParse' => true,
                                                'pickerReferer ' => 'default', 
                                                'pickerPosition' => 'bottom-right',
                                                'viewSelect' => 'decade',
                                                'initialDate' => date('Y'), 
                                            ),
                                        'read_only' => true,
                                        'attr' => array(
                                            'style' => 'display:none;',
                                            'input_group' => array(
                                                'prepend' => '.icon-calendar'
                                            ))                          
                            ));
        }
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();      
        $naturel = array_key_exists('naturel', $data) ? $data['naturel'] : null;
        
        if( $naturel ){
            $data['reine']['race'] = $this->race->getId();
            // L'année de la reine est identique à celle de la date de remérage quand le remérage est naturel
            $data['reine']['anneeReine'] = substr($data['date'], 6, 4);
            $event->setData($data);
        }        
    }
}