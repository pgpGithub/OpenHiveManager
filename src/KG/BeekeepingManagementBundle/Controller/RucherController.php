<?php
namespace KG\BeekeepingManagementBundle\Controller;
use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Entity\Exploitation;
use KG\BeekeepingManagementBundle\Form\Type\RucherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class RucherController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function viewAction(Rucher $rucher, $page)
    {
        $apiculteurExploitations = $rucher->getExploitation()->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $page < 1 ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $maxEmplacements    = $this->container->getParameter('max_emplacements_per_page');
        $emplacements       = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Emplacement')->getListByRucher($page, $maxEmplacements, $rucher->getId());
        $emplacements_count = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Emplacement')->countByRucher($rucher->getId()); 
        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_view_rucher',
            'pages_count'  => max ( ceil($emplacements_count / $maxEmplacements), 1),
            'route_params' => array('rucher_id' => $rucher->getId())
        );
        
        return $this->render('KGBeekeepingManagementBundle:Rucher:view.html.twig', 
                array(  'rucher'         => $rucher,
                        'emplacements'   => $emplacements,
                        'nbEmplacements' => $emplacements_count,
                        'pagination'     => $pagination));
    }
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}}) 
    */    
    public function deleteAction(Rucher $rucher)
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
        
        $em = $this->getDoctrine()->getManager();
        
        foreach ( $rucher->getEmplacements() as $emplacement){
            $ruche = $emplacement->getRuche();
            if ( $ruche ){
                $colonie = $ruche->getColonie();
                if( $colonie->getColoniesFilles()->isEmpty() ){
                    $em->remove($colonie);
                }
            }
        }
        
        $em->remove($rucher);
        $em->flush();
        //$this->getSession()->getFlashBag()->add('success','Rucher supprimé avec succès');
        return $this->redirect($this->generateUrl('kg_beekeeping_management_home'));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("exploitation", options={"mapping": {"exploitation_id" : "id"}}) 
    */    
    public function addAction(Exploitation $exploitation, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $exploitation->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $rucher = new Rucher();
        $form = $this->createForm(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $rucher->setExploitation($exploitation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Rucher créé avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $rucher->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Rucher:add.html.twig', 
                             array('form'         => $form->createView(),
                                   'exploitation' => $exploitation 
                            ));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}}) 
    */    
    public function updateAction(Rucher $rucher, Request $request)
    {
        $not_permitted = true;
        
        foreach ( $rucher->getExploitation()->getApiculteurExploitations() as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Rucher mis à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $rucher->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Rucher:update.html.twig', 
                             array('form'   => $form->createView(),
                                   'rucher' => $rucher 
                            ));
    }     
}