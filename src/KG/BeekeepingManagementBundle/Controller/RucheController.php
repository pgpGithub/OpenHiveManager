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

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Hausse;
use KG\BeekeepingManagementBundle\Entity\Emplacement;
use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Entity\Remerage;
use KG\BeekeepingManagementBundle\Form\Type\UpdateRemerageType;
use KG\BeekeepingManagementBundle\Form\Type\TranshumerType;
use KG\BeekeepingManagementBundle\Form\Type\RucheType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RucheController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}})  
    */    
    public function updateAction(Ruche $ruche, Request $request)
    {       
        if( !$this->getUser()->canDisplayExploitation($ruche->getRucher()->getExploitation()) || !$ruche->canBeUpdated() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new UpdateRemerageType(), $ruche->getColonie()->getRemerages()->last());
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Ruche mise à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $ruche->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:update.html.twig', 
                             array(
                                    'form'  => $form->createView(),
                                    'ruche' => $ruche
                ));
    }      
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}}) 
    */    
    public function viewAction(Ruche $ruche)
    {        
        if( !$this->getUser()->canDisplayExploitation($ruche->getRucher()->getExploitation()) ){
            throw new NotFoundHttpException('Page inexistante.');
        }

        $taches = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Tache')->getListByColonie($ruche->getColonie())->getResult();         
        $chart = $this->get('app.chart');
        
        return $this->render('KGBeekeepingManagementBundle:Ruche:view.html.twig',
                array(  'ruche' => $ruche,
                        'taches' => $taches,
                        'getPoidsParVisite' => $chart->getChartPoidsParVisite( $ruche ),
                ));        
    }  

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("emplacement", options={"mapping": {"emplacement_id" : "id"}})  
    */    
    public function addAction(Emplacement $emplacement, Request $request)
    {
        if( !$this->getUser()->canDisplayExploitation($emplacement->getRucher()->getExploitation()) || !$emplacement->isEmpty() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $ruche = new Ruche($emplacement);
        $form = $this->createForm(new RucheType, $ruche);
        
        if ($form->handleRequest($request)->isValid()){
                    
            // La date du remérage est la même que celle de la création de la colonie
            $ruche->getColonie()->getRemerages()[0]->setDate($ruche->getColonie()->getDateColonie());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche->getColonie());
            $em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Ruche créée avec succès');

            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $ruche->getRucher()->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:add.html.twig', 
                             array(
                                    'form'        => $form->createView(),
                                    'emplacement' => $emplacement
                ));
    }      
}