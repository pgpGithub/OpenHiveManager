<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Form\RucherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RucherController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function indexAction($page)
    {
        if ($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        
        $maxRuchers     = $this->container->getParameter('max_ruchers_per_page');
        $exploitationId = $this->getUser()->getExploitationEnCours()->getId();
        $ruchers        = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Rucher')->getListByExploitation($page, $maxRuchers, $exploitationId);
        $ruchers_count  = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Rucher')->countByExploitation($exploitationId); 
        
        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_home_rucher',
            'pages_count'  => max ( ceil($ruchers_count / $maxRuchers), 1),
            'route_params' => array()
        );
        
        return $this->render('KGBeekeepingManagementBundle:Rucher:index.html.twig', 
                            array(  
                                 'ruchers'      => $ruchers,
                                 'nbRuchers'    => $ruchers_count,
                                 'pagination'   => $pagination
                            )
        ); 
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function viewAction($id)
    {
        $rucher = $this->getDoctrine()->getManager()->getRepository('KGBeekeepingManagementBundle:Rucher')->find($id);
        return $this->render('KGBeekeepingManagementBundle:Rucher:view.html.twig', 
                            array( 
                                'rucher'    =>$rucher
                            )
        );
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