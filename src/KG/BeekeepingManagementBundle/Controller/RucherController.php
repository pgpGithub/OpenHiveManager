<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Form\RucherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RucherController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function viewAction(Rucher $rucher)
    {
        if( $rucher->getExploitation() != $this->getUser()->getExploitationEnCours()){
            throw new NotFoundHttpException('Page inexistante.');
        }
        return $this->render('KGBeekeepingManagementBundle:Rucher:view.html.twig', array( 'rucher' => $rucher ));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function addAction(Request $request)
    {
        $rucher = new Rucher();
        $form = $this->createForm(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $rucher->setExploitation($this->getUser()->getExploitationEnCours());
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Rucher créé avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('id' => $rucher->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Rucher:add.html.twig', array('form' => $form->createView()));
    } 
}