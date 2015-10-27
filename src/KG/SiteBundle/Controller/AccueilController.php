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
        $contact = new Contact();
        
        $form = $this->createForm(new ContactType(), $contact);
        
        if ($form->handleRequest($request)->isValid()){
            $message = \Swift_Message::newInstance()
                    ->setSubject($contact->getSujet()->getLibelle())
                    ->setFrom($this->container->getParameter('mailer_user'))
                    ->setBody($this->renderView('KGSiteBundle::mail.txt.twig', array('contact' => $contact)));
            
            if( $contact->getSujet()->getId() == 1 || $contact->getSujet()->getId() == 2){
                $message->setTo($this->container->getParameter('support'));
            }else{
                $message->setTo($this->container->getParameter('contact'));
            }
            $this->get('mailer')->send($message);            
            
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Message envoyé avec succès. Merci de nous avoir contacté.');
            
            return $this->redirect($this->generateUrl('kg_site_contact'));
        }

        return $this->render('KGSiteBundle:Accueil:contact.html.twig', 
                             array(
                                    'form'   => $form->createView()
                ));        
    }    

    public function participerAction()
    {      
        return $this->render('KGSiteBundle:Accueil:participer.html.twig'); 
    }      
}