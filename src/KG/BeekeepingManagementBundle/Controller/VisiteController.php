<?php

namespace KG\BeekeepingManagementBundle\Controller;

use KG\BeekeepingManagementBundle\Entity\Ruche;
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
    * @ParamConverter("ruche", options={"mapping": {"visite_id" : "id"}}) 
    */    
    public function viewAction(Visite $visite)
    {

    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    public function deleteAction(Visite $visite)
    {

    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("ruche", options={"mapping": {"ruche_id" : "id"}})  
    */    
    public function addAction(Ruche $ruche, Request $request)
    {
        
    } 
}