<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Entity\Rucher;
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
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}}) 
    */    
    public function viewAction(Ruche $ruche, $page)
    {
        $apiculteurExploitations = $ruche->getRucher()->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $maxVisites     = $this->container->getParameter('max_visites_per_page');
        $visites        = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Visite')->getListByRuche($page, $maxVisites, $ruche->getId());
        $visites_count  = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Visite')->countByRuche($ruche->getId()); 

        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_view_ruche',
            'pages_count'  => max ( ceil($visites_count / $maxVisites), 1),
            'route_params' => array('ruche_id' => $ruche->getId())
        );
        
        return $this->render('KGBeekeepingManagementBundle:Ruche:view.html.twig',
                array(  'ruche'       => $ruche,
                        'visites'     => $visites,
                        'nbVisites'    => $visites_count,
                        'pagination'  => $pagination
                ));        
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function deleteAction(Ruche $ruche)
    {

    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function addAction(Rucher $rucher, Request $request)
    {
        $exploitation = $rucher->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $ruche = new Ruche();
        $form = $this->createForm(new RucheType, $ruche);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $ruche->setRucher($rucher);
            $ruche->setExploitation($exploitation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Ruche créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $ruche->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:add.html.twig', 
                             array(
                                    'form'   => $form->createView(),
                                    'rucher' => $rucher
                ));
    } 
}