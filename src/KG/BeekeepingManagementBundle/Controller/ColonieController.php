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
use KG\BeekeepingManagementBundle\Entity\Reine;
use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Form\Type\ColonieType;
use KG\BeekeepingManagementBundle\Form\Type\UpdateColonieType;
use KG\BeekeepingManagementBundle\Form\Type\EnrucherType;
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
        $apiculteurExploitations = $colonie->getRucher()->getExploitation()->getApiculteurExploitations();
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
        $exploitation = $colonie->getRucher()->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || !$colonie->getRecoltes()->isEmpty() || !$colonie->getVisites()->isEmpty() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($colonie);
        $em->flush();

        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success('Colonie supprimée avec succès');
        
        return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $colonie->getRucher()->getId())));            
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}}) 
    */    
    public function updateAction(Colonie $colonie, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonie->getRuche()->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $ancienClippage = $colonie->getReine()->getClippage();
                
        $form = $this->createForm(new UpdateColonieType, $colonie);
        
        if ($form->handleRequest($request)->isValid()){
            
            if($ancienClippage && !$colonie->getReine()->getClippage()){
                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->danger('Le clippage ne peut pas être annulé');
            }else{
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($colonie);
                $em->flush();

                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->success('Colonie mise à jour avec succès');

                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonie->getId())));
            }
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
        
        foreach ( $colonieMere->getRuche()->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonieMere->getMorte() || $colonieMere->getRuche()->getCorps()->getNbcouvain() < 2 ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $colonie = $colonieMere->diviser($this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Origine')->findOneByLibelle("Division"));        
        $form = $this->createForm(new DiviserType($colonieMere->getDateColonie()), $colonie);
        
        if ($form->handleRequest($request)->isValid()){
            
            $colonieMere->getRuche()->getCorps()->diviser($colonie->getRuche()->getCorps()->getNbnourriture(), $colonie->getRuche()->getCorps()->getNbcouvain());
            $em = $this->getDoctrine()->getManager();
            $em->persist($colonie);       
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Colonie divisée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonie->getColonie()->getId())));
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
        $exploitation = $colonie->getRuche()->getEmplacement()->getRucher()->getExploitation();
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
            if(!($colonie->getCauses()->isEmpty() && empty($colonie->getAutreCause()))){ 
                $colonie->setMorte(true);                
                $em = $this->getDoctrine()->getManager();
                $em->persist($colonie);
                $em->remove($colonie->getRuche());
                $em->flush();

                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->success('Colonie déclarée morte avec succès');
                
                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonie->getId())));                          
            }
            else{
                $this->get('session')->getFlashBag()->add('danger','Veuillez renseigner au moins une cause de la mort');  
            }                     
        }
        
        return $this->render('KGBeekeepingManagementBundle:Colonie:tuer.html.twig', 
                             array('form'   => $form->createView(),
                                   'colonie' => $colonie, 
                            ));  
    }    
}    
