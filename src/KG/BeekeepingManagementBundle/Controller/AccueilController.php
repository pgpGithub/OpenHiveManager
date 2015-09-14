<?php

namespace KG\BeekeepingManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccueilController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')")
    */
    public function indexAction()
    {     
        return $this->render('KGBeekeepingManagementBundle::index.html.twig');
    }
}