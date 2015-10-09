<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Hausse;
use KG\BeekeepingManagementBundle\Entity\Emplacement;
use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Form\Type\UpdateRucheType;
use KG\BeekeepingManagementBundle\Form\Type\TranshumerType;
use KG\BeekeepingManagementBundle\Form\Type\AddRucheType;
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
        $apiculteurExploitations = $ruche->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations();
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
        
        $form = $this->createForm(new UpdateRucheType, $ruche);
        
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
    public function transhumerAction(Ruche $ruche, Request $request)
    {
        $apiculteurExploitations = $ruche->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations();
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

        $form = $this->createForm(new TranshumerType(), $ruche);
                
        if ($form->handleRequest($request)->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($ruche);
                $em->flush();

                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->success('Ruche transhumée avec succès');

                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $ruche->getEmplacement()->getRucher()->getId()))); 
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:transhumer.html.twig', 
                             array('form'  => $form->createView(),
                                   'ruche' => $ruche
                            ));      
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */      
    public function soustypesAction(Request $request)
    {
        $type_id  = $request->request->get('type_id');
        
        $em       = $this->getDoctrine()->getManager();
        $soustype = $em->getRepository('KGBeekeepingManagementBundle:SousTypeRuche')->findByTypeId($type_id);

        return new JsonResponse($soustype);
    }  
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}}) 
    */    
    public function viewAction(Request $request, Ruche $ruche, $page)
    {
        $apiculteurExploitations = $ruche->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations();
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
        
        if($ruche->getColonie()){    
            $query = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Visite')->getListByColonie($ruche->getColonie());    
        }
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            10,
            array(
                'defaultSortFieldName' => 'visite.date',
                'defaultSortDirection' => 'desc'
            )                
        );
        
        return $this->render('KGBeekeepingManagementBundle:Ruche:view.html.twig',
                array(  'ruche'       => $ruche,
                        'pagination'  => $pagination
                ));        
    }  

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("emplacement", options={"mapping": {"emplacement_id" : "id"}})  
    */    
    public function addAction(Emplacement $emplacement, Request $request)
    {
        $apiculteurExploitations = $emplacement->getRucher()->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $emplacement->getRuche() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $ruche = new Ruche();
        $form = $this->createForm(new AddRucheType, $ruche);
        
        if ($form->handleRequest($request)->isValid()){
                       
            $ruche->getColonie()->setRucher($emplacement->getRucher());
            $ruche->setEmplacement($emplacement);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche->getCorps());
            $em->persist($ruche);
            $em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Ruche créée avec succès');

            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $emplacement->getRucher()->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:add.html.twig', 
                             array(
                                    'form'        => $form->createView(),
                                    'emplacement' => $emplacement
                ));
    }     
}