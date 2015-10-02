<?php
namespace KG\BeekeepingManagementBundle\Controller;
use KG\BeekeepingManagementBundle\Entity\Transhumance;
use KG\BeekeepingManagementBundle\Entity\Colonie;
use KG\BeekeepingManagementBundle\Form\Type\TranshumanceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TranshumanceController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("colonie", options={"mapping": {"colonie_id" : "id"}})  
    */    
    public function viewAllAction(Colonie $colonie, $page)
    {
        $exploitation = $colonie->getRucher()->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( $not_permitted || $page < 1  || $colonie->getTranshumances()->isEmpty()){
            throw new NotFoundHttpException('Page inexistante.');
        }
 
        $maxTranshumances    = $this->container->getParameter('max_transhumances_per_page');
        $transhumances       = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Transhumance')->getListByColonie($page, $maxTranshumances, $colonie->getId());
        $transhumances_count = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Transhumance')->countByColonie($colonie->getId()); 
        $pagination = array(
            'page'         => $page,
            'route'        => 'kg_beekeeping_management_view_transhumances',
            'pages_count'  => max ( ceil($transhumances_count / $maxTranshumances), 1),
            'route_params' => array('colonie_id' => $colonie->getId())
        );
        
        return $this->render('KGBeekeepingManagementBundle:Transhumance:viewAll.html.twig', 
                array(  'colonie'          => $colonie,
                        'transhumances'   => $transhumances,
                        'nbTranshumances' => $transhumances_count,
                        'pagination' => $pagination));
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
         
        $transhumance = new Transhumance($colonie);
        
        $form = $this->createForm(new TranshumanceType, $transhumance);
        
        if ($form->handleRequest($request)->isValid()){
            //$transhumance->getColonie()->setRucher($transhumance->getRucherto());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($transhumance);
            $em->flush();
        
            $request->getSession()->getFlashBag()->add('success','Transhumance créée avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $transhumance->getColonie()->getRuche()->getId())));
        }
        return $this->render('KGBeekeepingManagementBundle:Transhumance:add.html.twig', 
                             array(
                                    'form'    => $form->createView(),
                                    'colonie' => $colonie
                ));        
    }    
}