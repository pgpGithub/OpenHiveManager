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
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function viewAction(Rucher $rucher, $page)
    {
        if( $page < 1 || $rucher->getExploitation() != $this->getUser()->getExploitationEnCours()){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $maxRuches     = $this->container->getParameter('max_ruches_per_page');
        $ruches        = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Ruche')->getListByRucher($page, $maxRuches, $rucher->getId());
        $ruches_count  = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Ruche')->countByRucher($rucher->getId()); 

        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_view_rucher',
            'pages_count'  => max ( ceil($ruches_count / $maxRuches), 1),
            'route_params' => array('rucher_id' => $rucher->getId())
        );
        
        return $this->render('KGBeekeepingManagementBundle:Rucher:view.html.twig', 
                array(  'rucher'      => $rucher,
                        'ruches'      => $ruches,
                        'nbRuches'    => $ruches_count,
                        'pagination'  => $pagination));
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function deleteAction(Rucher $rucher)
    {
        if( $rucher->getExploitation() != $this->getUser()->getExploitationEnCours()){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        if ($rucher->getImage() != null){
            $rucher->getImage()->setSupprime(true);           
        }
        $rucher->getLocalisation()->setSupprime(true);
        $rucher->setSupprime(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($rucher);
        $em->flush();

        //$this->getSession()->getFlashBag()->add('success','Rucher supprimé avec succès');
        return $this->redirect($this->generateUrl('kg_beekeeping_management_view_exploitation', array('exploitation_id' => $this->getUser()->getExploitationEnCours()->getId())));
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