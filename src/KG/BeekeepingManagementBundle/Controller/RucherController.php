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
use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Entity\Exploitation;
use KG\BeekeepingManagementBundle\Form\Type\RucherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RucherController extends Controller
{    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function viewAction(Request $request, Rucher $rucher, $page)
    {
        $apiculteurExploitations = $rucher->getExploitation()->getApiculteurExploitations();
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
        
        $apikey = $this->container->getParameter('apikey');
        
        $query = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Emplacement')->getListByRucher($rucher);    

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            10,
            array(
                'defaultSortFieldName' => 'e.nom',
            )                
        );        
        
        return $this->render('KGBeekeepingManagementBundle:Rucher:view.html.twig', 
                array(  'rucher'     => $rucher,
                        'apikey'     => $apikey,
                        'pagination' => $pagination
                    )
            );        
    }

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function viewColoniesMortesAction(Request $request, Rucher $rucher, $page)
    {
        $apiculteurExploitations = $rucher->getExploitation()->getApiculteurExploitations();
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
        
        $query = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Colonie')->getListMortesByRucher($rucher);    

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            10,
            array(
                'defaultSortFieldName' => 'colonie.numero',
            )                
        );        
        
        return $this->render('KGBeekeepingManagementBundle:Rucher:viewColoniesMortes.html.twig', 
                array(  'rucher'     => $rucher,
                        'pagination' => $pagination
                    )
            );        
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}}) 
    */    
    public function deleteAction(Rucher $rucher)
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
        
        if( !$not_permitted ){
            foreach ( $rucher->getEmplacements() as $emplacement){
                if( $emplacement->getRuche() || !$emplacement->getTranshumancesfrom()->isEmpty() || !$emplacement->getTranshumancesto()->isEmpty() ){
                    $not_permitted = true;
                    break;                
                }
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($rucher);
        $em->flush();
        
        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success('Rucher supprimé avec succès');
        
        return $this->redirect($this->generateUrl('kg_beekeeping_management_home'));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("exploitation", options={"mapping": {"exploitation_id" : "id"}}) 
    */    
    public function addAction(Exploitation $exploitation, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $exploitation->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $rucher = new Rucher($exploitation);
        $form = $this->createForm(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $rucher->setExploitation($exploitation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Rucher créé avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_home'));
        }
        return $this->render('KGBeekeepingManagementBundle:Rucher:add.html.twig', 
                             array('form'         => $form->createView(),
                                   'exploitation' => $exploitation 
                            ));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}}) 
    */    
    public function updateAction(Rucher $rucher, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $rucher->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Rucher mis à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_home'));
        }
        return $this->render('KGBeekeepingManagementBundle:Rucher:update.html.twig', 
                             array('form'   => $form->createView(),
                                   'rucher' => $rucher 
                            ));
    }     
}