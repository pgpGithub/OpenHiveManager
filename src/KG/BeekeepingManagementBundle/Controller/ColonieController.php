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

use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Form\Type\UpdateRemerageType;
use KG\BeekeepingManagementBundle\Form\Type\DiviserType;
use KG\BeekeepingManagementBundle\Form\Type\CauseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ColonieController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewAction(Colonie $colonie)
    {
        $apiculteurExploitations = $colonie->getRuche()->getRucher()->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
       
        return $this->render('KGBeekeepingManagementBundle:Colonie:view.html.twig', 
                array(  'colonie' => $colonie ));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function deleteAction(Colonie $colonie)
    {
        $exploitation = $colonie->getRuche()->getRucher()->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        foreach ( $colonie->getRemerages() as $remerage ){
            if( !$remerage->getReine()->getReinesFilles()->isEmpty() ){
                $not_permitted = true;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($colonie);
        $em->flush();

        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success('Colonie supprimée avec succès');
        
        return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $colonie->getRuche()->getRucher()->getId())));            
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}}) 
    */    
    public function updateAction(Colonie $colonie, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonie->getRuche()->getRucher()->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
                
        $form = $this->createForm(new UpdateRemerageType(), $colonie->getRemerages()->last());
        
        if ($form->handleRequest($request)->isValid()){
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($colonie);
            $em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Colonie mise à jour avec succès');

            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonie->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Colonie:update.html.twig', 
                             array('form'     => $form->createView(),
                                   'colonie' => $colonie 
                            ));
    } 

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonieMere", options={"mapping": {"colonie_id" : "id"}}) 
    */    
    public function diviserAction(Colonie $colonieMere, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonieMere->getRuche()->getRucher()->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonieMere->getMorte() || $colonieMere->getRuche()->getCorps()->getNbcouvain() < 2 ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $colonie = $colonieMere->diviser($this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Origine')->findOneByLibelle("Division"));        
        $form = $this->createForm(new DiviserType($colonieMere), $colonie);
        
        if ($form->handleRequest($request)->isValid()){
            
            $colonieMere->getRuche()->getCorps()->diviser($colonie->getRuche()->getCorps()->getNbnourriture(), $colonie->getRuche()->getCorps()->getNbcouvain());

            // La date du remérage est la même que celle de la création de la colonie
            $colonie->getRemerages()[0]->setDate($colonie->getDateColonie());
            
            // La date de la reine est la même que celle de la création de la colonie
            $colonie->getRemerages()[0]->getReine()->setAnneeReine($colonie->getDateColonie());
            
            
            $em = $this->getDoctrine()->getManager();      
            $em->persist($colonie);       
            $em->persist($colonieMere);  
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Colonie divisée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $colonie->getRuche()->getRucher()->getId())));  
        }

        return $this->render('KGBeekeepingManagementBundle:Colonie:diviser.html.twig', 
                             array('form'        => $form->createView(),
                                   'colonieMere' => $colonieMere
                            ));        
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function tuerAction(Colonie $colonie, Request $request)
    {
        $exploitation = $colonie->getRuche()->getRucher()->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }

        $form = $this->createForm(new CauseType, $colonie);
        
        if ($form->handleRequest($request)->isValid()){  
            $colonie->setMorte(true);
            $colonie->getRuche()->getEmplacement()->setRuche();
            $colonie->getRuche()->setEmplacement();
            $em = $this->getDoctrine()->getManager();
            $em->persist($colonie);
            $em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('La mort de la colonie a bien été enregistrée');

            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonie->getId())));                                            
        }
        
        return $this->render('KGBeekeepingManagementBundle:Colonie:tuer.html.twig', 
                             array('form'   => $form->createView(),
                                   'colonie' => $colonie, 
                            ));  
    }    
}    
