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
    private $anneeReine;
    
    /**
     * Constructor
     */
    public function __construct($race, $anneeReine)
    {
        $this->race       = $race;
        $this->anneeReine = $anneeReine;     
    }    
    
    public static function getSubscribedEvents()
    {
        return array(
            //FormEvents::PRE_SET_DATA  => 'preSetData',
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
        
        /*$accessor = PropertyAccess::createPropertyAccessor();
        $naturel = $accessor->getValue($data, 'naturel');
        
        $this->addOrRemoveForm($form, $naturel);*/ 
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        
        $naturel = array_key_exists('naturel', $data) ? $data['naturel'] : null;
        
        if($naturel){
            $data['reine']['race'] = $this->race;
            $data['reine']['anneeReine'] = $this->anneeReine;
        }      
    }
}