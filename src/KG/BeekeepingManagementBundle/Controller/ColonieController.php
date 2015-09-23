<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Entity\Reine;
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
        $apiculteurExploitations = $colonie->getExploitation()->getApiculteurExploitations();
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
        $rucher = $colonie->getExploitation();
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
        
        if( !$colonie->getColoniesFilles()->isEmpty() ){
            $this->get('session')->getFlashBag()->add('danger','Vous ne pouvez pas supprimer une colonie possédant des colonies filles');            
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonie->getId())));
        }else{
            $em = $this->getDoctrine()->getManager();
            $em->remove($colonie);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success','Colonie supprimée avec succès');
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $rucher->getId())));            
        }
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
                $this->get('session')->getFlashBag()->add('danger','Le clippage ne peut pas être annulé');
            }else{
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($colonie);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success','Colonie mise à jour avec succès');

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
        
        if( $not_permitted || $colonieMere->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $colonieFille = $colonieMere->diviser();
        
        $form = $this->createForm(new DiviserType, $colonieFille);
        
        if ($form->handleRequest($request)->isValid()){
            
            $colonieFille->getRuche()->setEmplacement($form->get('emplacement')->getData());
            $colonieFille->getRuche()->setColonie($colonieFille);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($colonieFille->getRuche()->getCorps());
            $em->persist($colonieFille);           
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Colonie divisée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonieFille->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Colonie:diviser.html.twig', 
                             array('form'         => $form->createView(),
                                   'colonieMere' => $colonieMere, 
                                   'colonieFille'=> $colonieFille
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

                $this->get('session')->getFlashBag()->add('success','Colonie déclarée morte avec succès');
                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $colonie->getId())));                          
            }
            else{
                $this->get('session')->getFlashBag()->add('danger','Veuillez renseigner au moins une cause de la mort');  
            }                     
        }
        
        return $this->render('KGBeekeepingManagementBundle:Colonie:tuer.html.twig', 
                             array('form'     => $form->createView(),
                                   'colonie' => $colonie, 
                            ));  
    }    
}    
