<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Emplacement;
use KG\BeekeepingManagementBundle\Entity\Ruche;
use KG\BeekeepingManagementBundle\Form\Type\UpdateRucheType;
use KG\BeekeepingManagementBundle\Form\Type\TranshumerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RucheController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}})  
    */    
    public function updateAction(Ruche $ruche, Request $request)
    {
        $apiculteurExploitations = $ruche->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations();
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
        
        $form = $this->createForm(new UpdateRucheType, $ruche);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($ruche);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Ruche mise à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $ruche->getId())));
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:update.html.twig', 
                             array(
                                    'form'  => $form->createView(),
                                    'ruche' => $ruche
                ));
    }      

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}})  
    */       
    public function transhumerAction(Ruche $ruche, Request $request)
    {
        $apiculteurExploitations = $ruche->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations();
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

        $ancienEmplacement = $ruche->getEmplacement();
        $form = $this->createForm(new TranshumerType(), $ruche);
                
        if ($form->handleRequest($request)->isValid()){
            if(!$ruche->getEmplacement()){
                $this->get('session')->getFlashBag()->add('danger','Veuillez choisir un emplacement sur lequel placer votre ruche');                 
            }
            elseif($ruche->getEmplacement()->getRuche()){
                $this->get('session')->getFlashBag()->add('danger','Cet emplacement est déjà occupé par une ruche');
            }
            else{
                $em = $this->getDoctrine()->getManager();
                $ruche->getEmplacement()->setRuche($ruche);

                $em->persist($ruche);

                if($ancienEmplacement){
                    $ancienEmplacement->setRuche(NULL);
                    $em->persist($ancienEmplacement);
                }

                $em->flush();

                $request->getSession()->getFlashBag()->add('success','Ruche transhumée avec succès');

                return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $ruche->getEmplacement()->getRucher()->getId())));
            }  
        }

        return $this->render('KGBeekeepingManagementBundle:Ruche:transhumer.html.twig', 
                             array('form'  => $form->createView(),
                                   'ruche' => $ruche
                            ));      
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    */      
    public function emplacementsAction(Request $request)
    {
        $rucher_id = $request->request->get('rucher_id');
        
        $em     = $this->getDoctrine()->getManager();
        $emplacements = $em->getRepository('KGBeekeepingManagementBundle:Emplacement')->findByRucherId($rucher_id);

        return new JsonResponse($emplacements);
    }      
}