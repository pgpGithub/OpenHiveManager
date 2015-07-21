<?php

namespace KG\BeekeepingManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccueilController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function indexAction($page)
    {
        if( $page < 1){
            throw new NotFoundHttpException('Page inexistante.');
        }
       
        $maxExploitations     = $this->container->getParameter('max_exploitations_per_page');
        $exploitations        = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Exploitation')->getListByApiculteur($page, $maxExploitations, $this->getUser()->getId());
        $exploitations_count  = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Exploitation')->countByApiculteur($this->getUser()->getId()); 
        
        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_home',
            'pages_count'  => max ( ceil($exploitations_count / $maxExploitations), 1),
            'route_params' => array()
        );
             
        return $this->render('KGBeekeepingManagementBundle::index.html.twig', 
                array(  //'exploitation' => $exploitation,
                        'exploitations'      => $exploitations,
                        'nbExploitations'    => $exploitations_count,
                        'pagination'         => $pagination));
    }
}