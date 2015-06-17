<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\LieuStock;
use KG\BeekeepingManagementBundle\Form\LieuStockType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LieuStockController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        
        $maxStocks     = $this->container->getParameter('max_lieuStocks_per_page');
        $lieuStocks_count  = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:LieuStock')->getNbLieuStockTotal();
        
        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_home_lieuStock',
            'pages_count'  => max ( ceil($lieuStocks_count / $maxStocks), 1),
            'route_params' => array()
        );
        
        $lieuStocks = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:LieuStock')->getList($page, $maxStocks);
        
        return $this->render('KGBeekeepingManagementBundle:LieuStock:index.html.twig', 
                            array(  
                                 'lieuStocks'      => $lieuStocks,
                                 'nbLieuStocks'    => $lieuStocks_count,
                                 'pagination'  => $pagination
                            )
        ); 
    }
    
    public function viewAction($id)
    {
        return $this->render('KGBeekeepingManagementBundle:LieuStock:view.html.twig', array('id' => $id));
    }
    
    public function addAction(Request $request)
    {
        $lieuStock = new LieuStock();
        $form = $this->get('form.factory')->create(new LieuStockType, $lieuStock);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($lieuStock);
            $em->flush();
        
        $request->getSession()->getFlashBag()->add('notice','Stock créé avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_home_lieuStock', array('id' => $lieuStock->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:LieuStock:add.html.twig', array('form' => $form->createView()));
    } 
}