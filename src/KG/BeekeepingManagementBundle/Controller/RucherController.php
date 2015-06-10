<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Form\RucherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RucherController extends Controller
{
    public function indexAction()
    {
        $repository   = $this->getDoctrine()->getManager()->getRepository('KGBeekeepingManagementBundle:Rucher');          
        $listeRuchers = $repository->findAll();
        return $this->render('KGBeekeepingManagementBundle:Rucher:index.html.twig', array( 'listeRuchers' => $listeRuchers )); 
    }
    
    public function viewAction($id)
    {
        return $this->render('KGBeekeepingManagementBundle:Rucher:view.html.twig', array('id' => $id));
    }
    
    public function addAction(Request $request)
    {
        $rucher = new Rucher();
        $form = $this->get('form.factory')->create(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
        $request->getSession()->getFlashBag()->add('notice','Rucher créé avec succès');
        
            //return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('id' => $rucher->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Rucher:add.html.twig', array('form' => $form->createView()));
    } 
}