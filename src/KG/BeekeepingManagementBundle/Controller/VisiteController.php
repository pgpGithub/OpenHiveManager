<?php
namespace KG\BeekeepingManagementBundle\Controller;
use KG\BeekeepingManagementBundle\Entity\Visite;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Form\Type\VisiteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class VisiteController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("visite", options={"mapping": {"visite_id" : "id"}}) 
    */    
    public function viewAction(Visite $visite)
    {
        $apiculteurExploitations = $visite->getColonie()->getRuche()->getEmplacement()->getRucher()->getExploitation()->getApiculteurExploitations();
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
       
        return $this->render('KGBeekeepingManagementBundle:Ruche:view.html.twig', 
                array(  'ruche' => $visite->getColonie()->getRuche() ));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function addAction(Colonie $colonie, Request $request)
    {
        $exploitation = $colonie->getRuche()->getEmplacement()->getRucher()->getExploitation();
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
        
        $lastVisite = $colonie->getVisites()->last();
        if ( $lastVisite ){
            if ( $lastVisite->getDate() >= $today ){
                throw new NotFoundHttpException('Page inexistante.');
            }
        }
 
        $visite = new Visite();
        $visite->setColonie($colonie);
        
        $form = $this->createForm(new VisiteType, $visite);
        
        if ($form->handleRequest($request)->isValid()){
                   
            $visite->getColonie()->setEtat($visite->getEtat());
            $visite->getColonie()->setAgressivite($visite->getAgressivite());
            $visite->getColonie()->getRuche()->getCorps()->setNbnourriture($visite->getNbnourriture());
            $visite->getColonie()->getRuche()->getCorps()->setNbcouvain($visite->getNbcouvain());
            $em = $this->getDoctrine()->getManager();
            $em->persist($visite);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Visite créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $visite->getColonie()->getRuche()->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Visite:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'colonie' => $colonie
                ));        
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("visite", options={"mapping": {"visite_id" : "id"}}) 
    */    
    public function updateAction(Visite $visite, Request $request)
    {
        $apiculteurExploitations = $visite->getColonie()->getRucher()->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $visite != $visite->getColonie()->getVisites()->last()){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new VisiteType, $visite);
        
        if ($form->handleRequest($request)->isValid()){
             
            $visite->getColonie()->setEtat($visite->getEtat());
            $visite->getColonie()->setAgressivite($visite->getAgressivite());
            $visite->getColonie()->getRuche()->getCorps()->setNbnourriture($visite->getNbnourriture());
            $visite->getColonie()->getRuche()->getCorps()->setNbcouvain($visite->getNbcouvain());
            $em = $this->getDoctrine()->getManager();
            $em->persist($visite);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Visite créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $visite->getColonie()->getRuche()->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Visite:update.html.twig', 
                             array(
                                    'form'  => $form->createView(),
                                    'visite' => $visite
                ));
    } 
}