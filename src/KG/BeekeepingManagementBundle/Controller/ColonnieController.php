<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Colonnie;
use KG\BeekeepingManagementBundle\Entity\Reine;
use KG\BeekeepingManagementBundle\Entity\Exploitation;
use KG\BeekeepingManagementBundle\Form\Type\ColonnieType;
use KG\BeekeepingManagementBundle\Form\Type\UpdateColonnieType;
use KG\BeekeepingManagementBundle\Form\Type\EnrucherType;
use KG\BeekeepingManagementBundle\Form\Type\DiviserType;
use KG\BeekeepingManagementBundle\Form\Type\CauseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ColonnieController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonnie", options={"mapping": {"colonnie_id" : "id"}})  
    */    
    public function viewAction(Colonnie $colonnie)
    {
        $apiculteurExploitations = $colonnie->getExploitation()->getApiculteurExploitations();
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
        
        if( !$colonnie->getColonniesFilles()->isEmpty() ){
            $this->get('session')->getFlashBag()->add('danger','Vous ne pouvez pas supprimer une colonnie possédant des colonnies filles');            
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonnie', array('colonnie_id' => $colonnie->getId())));
        }else{
            $colonnie->setSupprime(true);

            if( $colonnie->getRuche() ){
                if($colonnie->getRuche()->getEmplacement()){
                    $colonnie->getRuche()->setEmplacement(null);
                }
                $colonnie->getRuche()->setColonnie(null);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($colonnie);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success','Colonnie supprimée avec succès');
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_exploitation_colonnie', array('exploitation_id' => $exploitation->getId())));            
        }
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
        
        if( $not_permitted || $colonnie->getSupprime() || $colonnie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $ancienClippage = $colonnie->getReine()->getClippage();
                
        $form = $this->createForm(new UpdateColonnieType, $colonnie);
        
        if ($form->handleRequest($request)->isValid()){
            
            if($ancienClippage && !$colonnie->getReine()->getClippage()){
                $this->get('session')->getFlashBag()->add('danger','Le clippage ne peut pas être annulé');
            }else{
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($colonnie);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success','Colonnie mise à jour avec succès');

                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonnie', array('colonnie_id' => $colonnie->getId())));
            }
        }

        return $this->render('KGBeekeepingManagementBundle:Colonnie:update.html.twig', 
                             array('form'     => $form->createView(),
                                   'colonnie' => $colonnie 
                            ));
    } 

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonnieMere", options={"mapping": {"colonnie_id" : "id"}}) 
    */    
    public function diviserAction(Colonnie $colonnieMere, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonnieMere->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonnieMere->getSupprime() || $colonnieMere->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $colonnieFille = new Colonnie();
        $colonnieFille->setReine(new Reine());
        $colonnieFille->setExploitation($colonnieMere->getExploitation());
        $colonnieFille->getReine()->setRace($colonnieMere->getReine()->getRace());
        $colonnieFille->setAnneeColonnie(new \DateTime());
        $colonnieFille->setProvenanceColonnie($this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Provenance')->findOneByLibelle("Division"));
        $colonnieFille->setEtat($colonnieMere->getEtat());
        $colonnieFille->setAgressivite($colonnieMere->getAgressivite());
        $colonnieFille->setColonnieMere($colonnieMere);
        $colonnieMere->addColonniesFilles($colonnieFille);
        
        $form = $this->createForm(new DiviserType, $colonnieFille);
        
        if ($form->handleRequest($request)->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($colonnieFille);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Colonnie divisée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonnie', array('colonnie_id' => $colonnieFille->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Colonnie:diviser.html.twig', 
                             array('form'         => $form->createView(),
                                   'colonnieMere' => $colonnieMere, 
                                   'colonnieFille'=> $colonnieFille
                            ));        
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonnie", options={"mapping": {"colonnie_id" : "id"}})  
    */    
    public function tuerAction(Colonnie $colonnie, Request $request)
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
        
        if( $not_permitted || $colonnie->getSupprime() || $colonnie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }

        $form = $this->createForm(new CauseType, $colonnie);
        
        if ($form->handleRequest($request)->isValid()){
            if(!($colonnie->getCauses()->isEmpty() && empty($colonnie->getAutreCause()))){ 
                $colonnie->setMorte(true);

                if( $colonnie->getRuche() ){
                    if( $colonnie->getRuche()->getEmplacement() ){
                        $colonnie->getRuche()->setEmplacement(null);
                    }
                    $colonnie->getRuche()->setColonnie(null);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($colonnie);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success','Colonnie déclarée morte avec succès');
                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonnie', array('colonnie_id' => $colonnie->getId())));                          
            }
            else{
                $this->get('session')->getFlashBag()->add('danger','Veuillez renseigner au moins une cause de la mort');  
            }                     
        }
        
        return $this->render('KGBeekeepingManagementBundle:Colonnie:tuer.html.twig', 
                             array('form'     => $form->createView(),
                                   'colonnie' => $colonnie, 
                            ));  
    }    

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonnie", options={"mapping": {"colonnie_id" : "id"}}) 
    */    
    public function enrucherAction(Colonnie $colonnie, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonnie->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonnie->getSupprime() || $colonnie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }

        $ancienneRuche = $colonnie->getRuche();
        $form = $this->createForm(new EnrucherType(), $colonnie);
                
        if ($form->handleRequest($request)->isValid()){
            if(!$colonnie->getRuche()){
                $this->get('session')->getFlashBag()->add('danger','Veuillez choisir une ruche dans laquelle placer votre colonnie');                 
            }
            elseif($colonnie->getRuche()->getColonnie()){
                $this->get('session')->getFlashBag()->add('danger','Cette ruche est déjà occupée par une colonnie');
            }
            else{
                $em = $this->getDoctrine()->getManager();
                $colonnie->getRuche()->setColonnie($colonnie);

                $em->persist($colonnie);

                if($ancienneRuche){
                    $ancienneRuche->setColonnie(NULL);
                    $em->persist($ancienneRuche);
                }

                $em->flush();

                $request->getSession()->getFlashBag()->add('success','Colonnie enruchée avec succès');

                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $colonnie->getRuche()->getId())));
            }  
        }

        return $this->render('KGBeekeepingManagementBundle:Colonnie:enrucher.html.twig', 
                             array('form'     => $form->createView(),
                                   'colonnie' => $colonnie
                            ));        
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */      
    public function ruchesAction(Request $request)
    {
        $type_id         = $request->request->get('type_id');
        $exploitation_id = $request->request->get('exploitation_id');
        
        $em     = $this->getDoctrine()->getManager();
        $ruches = $em->getRepository('KGBeekeepingManagementBundle:Ruche')->findByTypeId($type_id, $exploitation_id);

        return new JsonResponse($ruches);
    } 
}    
