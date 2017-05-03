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
use KG\BeekeepingManagementBundle\Entity\Visite;
use KG\BeekeepingManagementBundle\Entity\HausseRuche;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Form\Type\VisiteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class VisiteController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("visite", options={"mapping": {"visite_id" : "id"}}) 
    */    
    public function viewAction(Visite $visite)
    {
        if( !$this->getUser()->canDisplayExploitation($visite->getColonie()->getRuche()->getRucher()->getExploitation()) ){
            throw new NotFoundHttpException('Page inexistante.');
        }
       
        return $this->render('KGBeekeepingManagementBundle:Visite:view.html.twig', 
                array(  'visite' => $visite ));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function addAction(Colonie $colonie, Request $request)
    {
        if( !( $this->getUser()->isResponsable($colonie()->getRuche()->getRucher()->getExploitation()) ||
               $this->getUser()->isApiculteur($colonie()->getRuche()->getRucher()->getExploitation()))
            || !$colonie->canHaveNewVisite() ){
            throw new NotFoundHttpException('Page inexistante.');
        }       
 
        $visite = new Visite($colonie);

        $em = $this->getDoctrine()->getManager();        
        $form = $this->createForm(new VisiteType($em), $visite);
        
        if ($form->handleRequest($request)->isValid()){
             
            // On retire toutes les tâches qui étaient cochées avant
            foreach( $visite->getTaches() as $tache){
                $visite->removeTache($tache);
            }
            
            // Et on recoche que les nouvelles pour que celle qui ne le sont plus ne le soient plus
            foreach( $form['taches']->getData() as $tache){    
                $visite->addTache($tache);
            }         
            
            $visite->getColonie()->getRuche()->getCorps()->setNbnourriture($visite->getNbnourriture());
            $visite->getColonie()->getRuche()->getCorps()->setNbcouvain($visite->getNbcouvain());
            
            foreach ( $visite->getColonie()->getRuche()->getHausses() as $hausse ){
                $em->remove($hausse);
                $visite->getColonie()->getRuche()->removeHauss($hausse);
            }
                                
            foreach ( $visite->getHausses() as $hausse ){
                $visite->getColonie()->getRuche()->addHauss(new HausseRuche($hausse));
            }
            
            $em->persist($visite);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Visite créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $visite->getColonie()->getRuche()->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Visite:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'colonie' => $colonie
                ));        
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("visite", options={"mapping": {"visite_id" : "id"}}) 
    */    
    public function updateAction(Visite $visite, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
                    
        if( !( $this->getUser()->isResponsable($visite->getColonie()->getRuche()->getRucher()->getExploitation()) ||
               $this->getUser()->isApiculteur($visite->getColonie()->getRuche()->getRucher()->getExploitation()))
            || !$visite->canBeUpdated() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new VisiteType($em), $visite);
        
        if ($form->handleRequest($request)->isValid()){

            // On retire toutes les tâches qui étaient cochées avant
            foreach( $visite->getTaches() as $tache){
                $visite->removeTache($tache);
            }
            
            // Et on recoche que les nouvelles pour que celle qui ne le sont plus ne le soient plus
            foreach( $form['taches']->getData() as $tache){    
                $visite->addTache($tache);
            }   
            
            $visite->getColonie()->getRuche()->getCorps()->setNbnourriture($visite->getNbnourriture());
            $visite->getColonie()->getRuche()->getCorps()->setNbcouvain($visite->getNbcouvain());

            foreach ( $visite->getColonie()->getRuche()->getHausses() as $hausse ){
                $em->remove($hausse);
                $visite->getColonie()->getRuche()->removeHauss($hausse);
            }
            
            foreach ( $visite->getHausses() as $hausse ){
                $visite->getColonie()->getRuche()->addHauss(new HausseRuche($hausse));
            }
            
            $em->persist($visite);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Visite mise à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $visite->getColonie()->getRuche()->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Visite:update.html.twig', 
                             array(
                                    'form'  => $form->createView(),
                                    'visite' => $visite
                ));
    } 
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewAllAction(Colonie $colonie)
    {       
        if( !$this->getUser()->canDisplayExploitation($colonie->getRuche()->getRucher()->getExploitation())){
            throw new NotFoundHttpException('Page inexistante.');
        }                 
        
        return $this->render('KGBeekeepingManagementBundle:Visite:viewAll.html.twig', 
                array( 'colonie' => $colonie ));
    }    
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewNourrissementsAction(Colonie $colonie)
    {       
        if( !$this->getUser()->canDisplayExploitation($colonie->getRuche()->getRucher()->getExploitation())){
            throw new NotFoundHttpException('Page inexistante.');
        }                 
        
        return $this->render('KGBeekeepingManagementBundle:Visite:viewNourrissements.html.twig', 
                array( 'colonie' => $colonie ));
    }      

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewTraitementsAction(Colonie $colonie)
    {       
        if( !$this->getUser()->canDisplayExploitation($colonie->getRuche()->getRucher()->getExploitation())){
            throw new NotFoundHttpException('Page inexistante.');
        }                 
        
        return $this->render('KGBeekeepingManagementBundle:Visite:viewTraitements.html.twig', 
                array( 'colonie' => $colonie ));
    }      
    
}