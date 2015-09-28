<?php
namespace KG\BeekeepingManagementBundle\Controller;
use KG\BeekeepingManagementBundle\Entity\RecolteRucher;
use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Form\Type\RecolteRucherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RecolteRucherController extends Controller
{   
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
        
        $today = new \DateTime();
        $today->setTime('00', '00', '00');
        
        $lastRecolte = $rucher->getRecoltesrucher()->last();
        if ( $lastRecolte ){
            if ( $lastRecolte->getDate() >= $today ){
                throw new NotFoundHttpException('Page inexistante.');
            }
        }
 
        $recolte = new RecolteRucher();
        $recolte->setRucher($rucher);
        
        $form = $this->createForm(new RecolteRucherType( $this->getDoctrine()->getManager() ), $recolte);
        
        if ($form->handleRequest($request)->isValid()){
                   
            /*$visite->getColonie()->setEtat($visite->getEtat());
            $visite->getColonie()->setAgressivite($visite->getAgressivite());
            $visite->getColonie()->getRuche()->getCorps()->setNbnourriture($visite->getNbnourriture());
            $visite->getColonie()->getRuche()->getCorps()->setNbcouvain($visite->getNbcouvain());*/
            /*$em = $this->getDoctrine()->getManager();
            $em->persist($recolte);
            $em->flush();*/
        
            $request->getSession()->getFlashBag()->add('success','Récolte créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $rucher->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Recolte:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'rucher' => $rucher
                ));        
    }
}