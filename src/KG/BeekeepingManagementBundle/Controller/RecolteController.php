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

use Symfony\Component\HttpFoundation\Request;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Entity\Recolte;
use KG\BeekeepingManagementBundle\Form\Type\RecolteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RecolteController extends Controller
{

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function addAction(Colonie $colonie, Request $request)
    {
        $apiculteurExploitations = $colonie->getRuche()->getRucher()->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonie->getRuche()->getHausses()->isEmpty()){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $recolte = new Recolte($colonie);
        $form = $this->createForm(new RecolteType, $recolte);
        
        if ($form->handleRequest($request)->isValid()){

            $em = $this->getDoctrine()->getManager();
            
            foreach( $colonie->getRuche()->getHausses() as $hausse ){
                $fieldname = 'hausse_' . $hausse->getId();
                $recoltes = $form->get($fieldname)->getData();
                $restant = $hausse->getNbplein() - $recoltes;
                
                // Si hausse vide alors on la supprime
                if( $restant > 0 ){
                    $hausse->setNbplein( $restant );
                    $em->persist($hausse);
                }else{
                    $em->remove($hausse);
                }
            }                    
                    
            $em->persist($recolte);
            $em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Récolte créée avec succès');

            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $colonie->getRuche()->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Recolte:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'colonie' => $colonie
                ));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewAllAction(Request $request, Colonie $colonie, $page)
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
        
        if( $not_permitted || $page < 1  || $colonie->getRecoltes()->isEmpty()){
            throw new NotFoundHttpException('Page inexistante.');
        }
 
        $query = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Recolte')->getListByColonie($colonie);    
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            30,
            array(
                'defaultSortFieldName' => 'r.date',
                'defaultSortDirection' => 'desc'
            )  
        );
        
        return $this->render('KGBeekeepingManagementBundle:Recolte:viewAll.html.twig', 
                array(  'colonie'    => $colonie,
                        'pagination' => $pagination));
    }
    
}