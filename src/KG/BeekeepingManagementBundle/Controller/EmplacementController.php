<?php

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
    public function viewAction(Emplacement $emplacement)
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
        
        return $this->render('KGBeekeepingManagementBundle:Emplacement:view.html.twig',
                array( 'emplacement' => $emplacement ));        
    }

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
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
    
        if( $emplacement->getRuche() ){
            $this->get('session')->getFlashBag()->add('danger','Vous ne pouvez pas supprimer un emplacement occupé par une ruche'); 
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_emplacement', array('emplacement_id' => $emplacement->getId())));                        
        }else{
            //$emplacement->setSupprime(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($emplacement);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success','Emplacement supprimé avec succès');
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $emplacement->getRucher()->getId())));            
        }
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
        
        $emplacement = new Emplacement();
        $form = $this->createForm(new EmplacementType, $emplacement);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $emplacement->setRucher($rucher);
            $em = $this->getDoctrine()->getManager();
            $em->persist($emplacement);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Emplacement créé avec succès');
        
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
        
        $form = $this->createForm(new RucheType, $emplacement);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($emplacement);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Emplacement mis à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_emplacement', array('emplacement_id' => $emplacement->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Emplacement:update.html.twig', 
                             array(
                                    'form'  => $form->createView(),
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