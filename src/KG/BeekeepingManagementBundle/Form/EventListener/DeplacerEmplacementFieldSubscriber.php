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
use Doctrine\ORM\EntityRepository;
 
class DeplacerEmplacementFieldSubscriber implements EventSubscriberInterface
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
 
    private function addEmplacementForm($form, $rucher = null)
    {
        $formOptions = array(
            'class'         => 'KGBeekeepingManagementBundle:Emplacement',
            'choice_label'  => 'numero',
            'empty_value'   => '',
            'attr'          => array(
                'class' => 'emplacement_selector'
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
        $form = $event->getForm();
        $this->addEmplacementForm($form);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $rucher = array_key_exists('rucher', $data) ? $data['rucher'] : null;
        $this->addEmplacementForm($form, $rucher);
    }
}