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
use KG\BeekeepingManagementBundle\Entity\Transhumance;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Form\Type\TranshumanceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TranshumanceController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewAllAction(Colonie $colonie)
    {       
        if( !$this->getUser()->canDisplayExploitation($colonie->getRuche()->getRucher()->getExploitation()) ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        return $this->render('KGBeekeepingManagementBundle:Transhumance:viewAll.html.twig', 
                array( 'colonie' => $colonie ));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function addAction(Colonie $colonie, Request $request)
    {
        if( !( $this->getUser()->isResponsable($colonie->getRuche()->getRucher()->getExploitation()) ||
               $this->getUser()->isApiculteur($colonie->getRuche()->getRucher()->getExploitation()))
            || !$colonie->canHaveNewTranshumance()){
            throw new NotFoundHttpException('Page inexistante.');
        }       
         
        $transhumance = new Transhumance($colonie);
        
        $form = $this->createForm(new TranshumanceType, $transhumance);
        
        if ($form->handleRequest($request)->isValid()){          
            
            $transhumance->getColonie()->getRuche()->setEmplacement($transhumance->getEmplacementto());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($transhumance);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Transhumance créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $transhumance->getColonie()->getRuche()->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Transhumance:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'colonie' => $colonie
                ));        
    }    
}