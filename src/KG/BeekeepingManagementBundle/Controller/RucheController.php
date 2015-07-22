<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Form\RucheType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RucheController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function viewAction(Ruche $ruche)
    {
        //if( $rucher->getExploitation() != $this->getUser()->getExploitationEnCours()){
        //    throw new NotFoundHttpException('Page inexistante.');
        //}
        //return $this->render('KGBeekeepingManagementBundle:Ruche:view.html.twig', array( 'ruche' => $ruche ));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function deleteAction(Ruche $ruche)
    {

    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function addAction(Request $request)
    {
        $ruche = new Ruche();
        $form = $this->createForm(new RucheType, $ruche);
        
        if ($form->handleRequest($request)->isValid()){
                        
            //$ruche->setRucher($this->getUser()->getExploitationEnCours());
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($ruche);
            //$em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Ruche créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('id' => $ruche->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:add.html.twig', array('form' => $form->createView()));
    } 
}