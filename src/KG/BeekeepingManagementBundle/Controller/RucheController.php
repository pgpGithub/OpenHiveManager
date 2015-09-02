<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Entity\Exploitation;
use KG\BeekeepingManagementBundle\Form\Type\RucheType;
use KG\BeekeepingManagementBundle\Form\Type\TranshumerType;
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
    public function viewAction(Ruche $ruche, $page)
    {
        $apiculteurExploitations = $ruche->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $ruche->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $maxVisites     = $this->container->getParameter('max_visites_per_page');
        
        if($ruche->getColonnie()){
            $visites        = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Visite')->getListByColonnie($page, $maxVisites, $ruche->getColonnie()->getId());
            $visites_count  = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Visite')->countByColonnie($ruche->getColonnie()->getId()); 
        }
        
        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_view_ruche',
            'pages_count'  => max ( ceil($visites_count / $maxVisites), 1),
            'route_params' => array('ruche_id' => $ruche->getId())
        );
        
        return $this->render('KGBeekeepingManagementBundle:Ruche:view.html.twig',
                array(  'ruche'       => $ruche,
                        'visites'     => $visites,
                        'nbVisites'   => $visites_count,
                        'pagination'  => $pagination
                ));        
    }

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}}) 
    */    
    public function deleteAction(Ruche $ruche)
    {
        $exploitation = $ruche->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $ruche->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
    
        $ruche->setSupprime(true);
        $ruche->setColonnie(NULL);
        $em = $this->getDoctrine()->getManager();
        $em->persist($ruche);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success','Ruche supprimée avec succès');
        return $this->redirect($this->generateUrl('kg_beekeeping_management_view_exploitation_ruche', array('exploitation_id' => $exploitation->getId())));        
    }

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}})  
    */    
    public function updateAction(Ruche $ruche, Request $request)
    {
        $apiculteurExploitations = $ruche->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $ruche->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new RucheType, $ruche);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Ruche mise à jour avec succès');
        
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
    * @ParamConverter("exploitation", options={"mapping": {"exploitation_id" : "id"}})  
    */    
    public function addFromExploitationAction(Exploitation $exploitation, Request $request)
    {
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $exploitation->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $ruche = new Ruche();
        $form = $this->createForm(new RucheType, $ruche);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $ruche->setExploitation($exploitation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Ruche créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $ruche->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:addFromExploitation.html.twig', 
                             array(
                                    'form'         => $form->createView(),
                                    'exploitation' => $exploitation
                ));
    }     

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}})  
    */       
    public function transhumerAction(Ruche $ruche, Request $request)
    {
        $apiculteurExploitations = $ruche->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $ruche->getSupprime() ){
            throw new NotFoundHttpException('Page inexistante.');
        }

        $ancienEmplacement = $ruche->getEmplacement();
        $form = $this->createForm(new TranshumerType(), $ruche);
                
        if ($form->handleRequest($request)->isValid()){
            if(!$ruche->getEmplacement()){
                $this->get('session')->getFlashBag()->add('danger','Veuillez choisir un emplacement sur lequel placer votre ruche');                 
            }
            elseif($ruche->getEmplacement()->getRuche()){
                $this->get('session')->getFlashBag()->add('danger','Cet emplacement est déjà occupé par une ruche');
            }
            else{
                $em = $this->getDoctrine()->getManager();
                $ruche->getEmplacement()->setRuche($ruche);

                $em->persist($ruche);

                if($ancienEmplacement){
                    $ancienEmplacement->setRuche(NULL);
                    $em->persist($ancienEmplacement);
                }

                $em->flush();

                $request->getSession()->getFlashBag()->add('success','Ruche transhumée avec succès');

                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $ruche->getEmplacement()->getRucher()->getId())));
            }  
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:transhumer.html.twig', 
                             array('form'  => $form->createView(),
                                   'ruche' => $ruche
                            ));      
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */      
    public function emplacementsAction(Request $request)
    {
        $rucher_id = $request->request->get('rucher_id');
        
        $em     = $this->getDoctrine()->getManager();
        $emplacements = $em->getRepository('KGBeekeepingManagementBundle:Emplacement')->findByRucherId($rucher_id);

        return new JsonResponse($emplacements);
    }    
}