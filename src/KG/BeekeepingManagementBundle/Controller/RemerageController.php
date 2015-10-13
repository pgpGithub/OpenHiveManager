<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Remerage;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Entity\Reine;
use KG\BeekeepingManagementBundle\Form\Type\RemerageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RemerageController extends Controller
{   
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}}) 
    */    
    public function addAction(Colonie $colonie, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $colonie->getRucher()->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $colonie->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $lastRemerage = $colonie->getRemerages()->last();
        // On initialise l'année à celle de la dernière reine : remérage naturel donc l'année ne peut pas être plus ancienne
        $reine = new Reine($lastRemerage->getReine()->getAnneeReine(), $lastRemerage->getReine()->getRace());
        $remerage = new Remerage($reine);
        $remerage->setColonie($colonie);
        
        $form = $this->createForm(new RemerageType($lastRemerage->getDate()), $remerage);
                
        if ($form->handleRequest($request)->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($remerage);
            $em->flush();
            
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Remérage naturel créé avec succès');
            
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonie', array('colonie_id' => $remerage->getColonie()->getId())));                
        }

        return $this->render('KGBeekeepingManagementBundle:Remerage:add.html.twig', 
                             array('form'    => $form->createView(),
                                   'colonie' => $colonie
                            ));        
    }
}    
