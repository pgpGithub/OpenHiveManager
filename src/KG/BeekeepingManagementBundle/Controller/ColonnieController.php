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
        
        if( $not_permitted || $page < 1 ){
            throw new NotFoundHttpException('Page inexistante.');
        }
       
        return $this->render('KGBeekeepingManagementBundle:Colonnie:view.html.twig', 
                array(  'colonnie' => $colonnie ));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function deleteAction(Colonnie $colonnie)
    {
        $apiculteurExploitations = $colonnie->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getValues()->getApiculteur->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $colonnie->setSupprime(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($colonnie);
        $em->flush();

        //$this->getSession()->getFlashBag()->add('success','Rucher supprimé avec succès');
        return $this->redirect($this->generateUrl('kg_beekeeping_management_view_exploitation_colonnie', array('exploitation_id' => $this->getUser()->getExploitationEnCours()->getId())));
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
        
        if( $not_permitted ){
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
}