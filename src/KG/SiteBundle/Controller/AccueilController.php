<?php

namespace KG\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use KG\SiteBundle\Form\Type\ContactType;
use KG\SiteBundle\Entity\Contact;

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
    
    public function contactAction(Request $request)
    {          
        return $this->render('KGSiteBundle:Accueil:contact.html.twig');        
    }    

    public function participerAction()
    {      
        return $this->render('KGSiteBundle:Accueil:participer.html.twig'); 
    }      
}