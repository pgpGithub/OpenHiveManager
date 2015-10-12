<?php

namespace KG\BeekeepingManagementBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Entity\Recolte;
use KG\BeekeepingManagementBundle\Form\Type\RecolteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RecolteController extends Controller
{

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function addAction(Colonie $colonie, Request $request)
    {
        $apiculteurExploitations = $colonie->getRucher()->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonie->getRuche()->getHausses()->isEmpty() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $recolte = new Recolte($colonie);
        $form = $this->createForm(new RecolteType, $recolte);
        
        if ($form->handleRequest($request)->isValid()){
                       
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche);
            $em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Récolte créée avec succès');

            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $colonie->getRuche()->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Recolte:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'colonie' => $colonie
                ));
    }     
}