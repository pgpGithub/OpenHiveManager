<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Colonnie;
use KG\BeekeepingManagementBundle\Entity\Exploitation;
use KG\BeekeepingManagementBundle\Form\ColonnieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ColonnieController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonnie", options={"mapping": {"colonnie_id" : "id"}})  
    */    
    public function viewAction(Colonnie $colonnie, $page)
    {
        $apiculteurExploitations = $colonnie->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $page < 1 || $colonnie->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
       
        return $this->render('KGBeekeepingManagementBundle:Colonnie:view.html.twig', 
                array(  'colonnie' => $colonnie ));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonnie", options={"mapping": {"colonnie_id" : "id"}})  
    */    
    public function deleteAction(Colonnie $colonnie)
    {
        $exploitation = $colonnie->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonnie->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $colonnie->setSupprime(true);
        $colonnie->getRuche()->setColonnie(NULL);
        $em = $this->getDoctrine()->getManager();
        $em->persist($colonnie);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success','Colonnie supprimée avec succès');
        return $this->redirect($this->generateUrl('kg_beekeeping_management_view_exploitation_colonnie', array('exploitation_id' => $exploitation->getId())));
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
        
        if( $not_permitted || $exploitation->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $colonnie = new Colonnie();
        $form = $this->createForm(new ColonnieType, $colonnie);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $colonnie->setExploitation($exploitation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($colonnie);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Colonnie créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonnie', array('colonnie_id' => $colonnie->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Colonnie:add.html.twig', 
                             array('form'         => $form->createView(),
                                   'exploitation' => $exploitation 
                            ));
    } 

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonnie", options={"mapping": {"colonnie_id" : "id"}}) 
    */    
    public function updateAction(Colonnie $colonnie, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonnie->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonnie->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new ColonnieType, $colonnie);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($colonnie);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Colonnie mise à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonnie', array('colonnie_id' => $colonnie->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Colonnie:update.html.twig', 
                             array('form'     => $form->createView(),
                                   'colonnie' => $colonnie 
                            ));
    } 
}    
    