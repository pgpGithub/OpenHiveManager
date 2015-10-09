<?php
namespace KG\BeekeepingManagementBundle\Controller;
use KG\BeekeepingManagementBundle\Entity\RecolteRucher;
use KG\BeekeepingManagementBundle\Entity\RecolteRuche;
use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Form\Type\RecolteRucherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RecolteRucherController extends Controller
{   
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function viewAllAction(Request $request, Rucher $rucher, $page)
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
        
        if( $not_permitted || $page < 1  || $rucher->getRecoltesrucher()->isEmpty()){
            throw new NotFoundHttpException('Page inexistante.');
        }
 
        $query      = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:RecolteRucher')->getListByRucher($rucher);  
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            30,
            array(
                'defaultSortFieldName' => 'recolte.date',
                'defaultSortDirection' => 'desc'
            )  
        );
        
        return $this->render('KGBeekeepingManagementBundle:RecolteRucher:viewAll.html.twig', 
                array(  'rucher'     => $rucher,
                        'pagination' => $pagination));
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
               
        $recolterucher = new RecolteRucher();
        $recolterucher->setRucher($rucher);

        $form = $this->createForm(new RecolteRucherType( $this->getDoctrine()->getManager() ), $recolterucher);
        
        if ($form->handleRequest($request)->isValid()){
                
            $em = $this->getDoctrine()->getManager();
            
            //Si il y a déjà une récolte aujourd'hui, on la complète
            $lastRecolte   = $rucher->getRecoltesrucher()->last();      
            if ( $lastRecolte ){
                if ( $lastRecolte->getDate() == $recolterucher->getDate() ){
                    $recolterucher = $lastRecolte;
                }
            } 
            
            //Pour chaque ruche récoltée on créé une recolte de ruche
            foreach( $form->get('ruches')->getData() as $ruche){
                $recolteruche = new RecolteRuche( $ruche, $recolterucher);
                
                //On vérifie quand même qu'il y ait des hausses
                if( !$recolteruche->getHausses()->isEmpty() ){
                    $recolterucher->addRecoltesruche($recolteruche);
                }
                
                //On sauvegarde la ruche car elle n'a plus de hausse
                $em->persist($ruche);
            }

            $em->persist($recolterucher);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Récolte créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $rucher->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:RecolteRucher:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'rucher' => $rucher
                ));        
    }
}