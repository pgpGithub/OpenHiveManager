<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Stock;
use KG\BeekeepingManagementBundle\Form\StockType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StockController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        
        $maxStocks     = $this->container->getParameter('max_stocks_per_page');
        $stocks_count  = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Stock')->getNbStockTotal();
        
        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_home_stock',
            'pages_count'  => max ( ceil($stocks_count / $maxStocks), 1),
            'route_params' => array()
        );
        
        $stocks = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Stock')->getList($page, $maxStocks);
        
        return $this->render('KGBeekeepingManagementBundle:Stock:index.html.twig', 
                            array(  
                                 'stocks'      => $stocks,
                                 'nbStocks'    => $stocks_count,
                                 'pagination'  => $pagination
                            )
        ); 
    }
    
    public function viewAction($id)
    {
        return $this->render('KGBeekeepingManagementBundle:Stock:view.html.twig', array('id' => $id));
    }
    
    public function addAction(Request $request)
    {
        $stock = new Stock();
        $form = $this->get('form.factory')->create(new StockType, $stock);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($stock);
            $em->flush();
        
        $request->getSession()->getFlashBag()->add('notice','Stock créé avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_home_stock', array('id' => $stock->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Stock:add.html.twig', array('form' => $form->createView()));
    } 
}