<?php

namespace KG\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {      
        return $this->render('KGSiteBundle:Accueil:index.html.twig'); 
    }
    
    public function cguAction()
    {      
        return $this->render('KGSiteBundle:Accueil:cgu.html.twig'); 
    }
    
    public function contactAction()
    {      
        return $this->render('KGSiteBundle:Accueil:contact.html.twig'); 
    }    
}