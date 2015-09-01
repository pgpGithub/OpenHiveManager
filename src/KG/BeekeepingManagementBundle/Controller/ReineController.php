<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Reine;
use KG\BeekeepingManagementBundle\Form\Type\RenouvelerReineType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use \DateTime;

class ReineController extends Controller
{  
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("reine", options={"mapping": {"reine_id" : "id"}}) 
    */    
    public function renouvelerAction(Reine $reine, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $reine->getColonnie()->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $reine->getColonnie()->getSupprime() || $reine->getColonnie()->getMorte() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new RenouvelerReineType(), $reine);
                
        if ($form->handleRequest($request)->isValid()){
            $reine->setProvenanceReine($this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:ProvenanceReine')->findOneByLibelle("Renouvellement"));
            $reine->setAnneeReine(new DateTime(date("Y-m-d")));
            $em = $this->getDoctrine()->getManager();
            $em->persist($reine);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success','Reine renouvelée avec succès');
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_colonnie', array('colonnie_id' => $reine->getColonnie()->getId())));                
        }

        return $this->render('KGBeekeepingManagementBundle:Reine:renouveler.html.twig', 
                             array('form'     => $form->createView(),
                                   'colonnie' => $reine->getColonnie()
                            ));        
    }
}    