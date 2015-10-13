<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Remerage;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Entity\Reine;
use KG\BeekeepingManagementBundle\Form\Type\RemerageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RemerageController extends Controller
{   
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewAllAction(Request $request, Colonie $colonie, $page)
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
        
        if( $not_permitted || $page < 1  || $colonie->getRemerages()->isEmpty()){
            throw new NotFoundHttpException('Page inexistante.');
        }
 
        $query = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Remerage')->getListByColonie($colonie);    
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
        
        return $this->render('KGBeekeepingManagementBundle:Remerage:viewAll.html.twig', 
                array(  'colonie'    => $colonie,
                        'pagination' => $pagination));
    }    
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}}) 
    */    
    public function addAction(Colonie $colonie, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonie->getRucher()->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $lastRemerage = $colonie->getRemerages()->last();
        $reine = new Reine(null, $lastRemerage->getReine()->getRace());
        $remerage = new Remerage($reine);
        $remerage->setColonie($colonie);
        
        $form = $this->createForm(new RemerageType($lastRemerage->getDate()), $remerage);
                
        if ($form->handleRequest($request)->isValid()){
            
            // L'année de la reine est identique à celle de la date de remérage quand le remérage est naturel
            if($remerage->getNaturel()){
                $remerage->getReine()->setAnneeReine($remerage->getDate());
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($remerage);
            $em->flush();
            
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Remérage naturel créé avec succès');
            
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $remerage->getColonie()->getId())));                
        }

        return $this->render('KGBeekeepingManagementBundle:Remerage:add.html.twig', 
                             array('form'    => $form->createView(),
                                   'colonie' => $colonie
                            ));        
    }
}    
