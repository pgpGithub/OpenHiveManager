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

use KG\BeekeepingManagementBundle\Entity\Emplacement;
use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Form\Type\EmplacementType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmplacementController extends Controller
{   
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("emplacement", options={"mapping": {"emplacement_id" : "id"}}) 
    */    
    public function deleteAction(Emplacement $emplacement)
    {
        $exploitation = $emplacement->getRucher()->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || !$emplacement->getTranshumancesfrom()->isEmpty() || !$emplacement->getTranshumancesto()->isEmpty() || $emplacement->getRuche()){
            throw new NotFoundHttpException('Page inexistante.');
        }
    
        $em = $this->getDoctrine()->getManager();
        $em->remove($emplacement);
        $em->flush();

        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success('Emplacement supprimé avec succès');

        return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $emplacement->getRucher()->getId())));            
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function addAction(Rucher $rucher, Request $request)
    {
        $exploitation = $rucher->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
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
        
        $emplacement = new Emplacement($rucher);
        $form = $this->createForm(new EmplacementType, $emplacement);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($emplacement);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Emplacement créé avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $emplacement->getRucher()->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Emplacement:add.html.twig', 
                             array(
                                    'form'   => $form->createView(),
                                    'rucher' => $rucher
                ));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("emplacement", options={"mapping": {"emplacement_id" : "id"}})  
    */    
    public function updateAction(Emplacement $emplacement, Request $request)
    {
        $apiculteurExploitations = $emplacement->getRucher()->getExploitation()->getApiculteurExploitations();
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
        
        $form = $this->createForm(new EmplacementType, $emplacement);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($emplacement);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Emplacement mis à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $emplacement->getRucher()->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Emplacement:update.html.twig', 
                             array(
                                    'form'        => $form->createView(),
                                    'emplacement' => $emplacement
                ));
    }  
    
    /**
    * @Security("has_role('ROLE_USER')")
    */      
    public function emplacementsAction(Request $request)
    {
        $rucher_id = $request->request->get('rucher_id');
        
        $em           = $this->getDoctrine()->getManager();
        $emplacements = $em->getRepository('KGBeekeepingManagementBundle:Emplacement')->findByRucherId($rucher_id);

        return new JsonResponse($emplacements);
    }      
}