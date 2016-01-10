<?php

/* 
 * Copyright (C) 2015 Kévin Grenèche < kevin.greneche at openhivemanager.org >
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace KG\BeekeepingManagementBundle\Controller;
use KG\BeekeepingManagementBundle\Entity\Rucher;
use KG\BeekeepingManagementBundle\Entity\Exploitation;
use KG\BeekeepingManagementBundle\Form\Type\RucherType;
use KG\BeekeepingManagementBundle\Form\Type\RucherQRCodesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
    public function printQRCodeAction(Request $request, Rucher $rucher)
    {       
        if( !$this->getUser()->canDisplayExploitation($rucher->getExploitation()) ){
            throw new NotFoundHttpException('Page inexistante.');
        }
              
        $form = $this->createForm(new RucherQRCodesType($this->getDoctrine()->getManager()), $rucher);
        
        if ($form->handleRequest($request)->isValid()){    
            return $this->downloadQRCodeFile($rucher, $form['ruches']->getData());
        }
        return $this->render('KGBeekeepingManagementBundle:Rucher:printQRCodes.html.twig', 
                             array('form'   => $form->createView(),
                                   'rucher' => $rucher 
                            ));        
    }

    /**
    * @Security("has_role('ROLE_USER')")
    */    
    private function downloadQRCodeFile(Rucher $rucher, $ruches)
    {       
        //Création de l'objet phpWord pour le fichier word
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        //Création du path pour gérer les fichiers temporaires
        $path = $this->get('kernel')->getRootDir(). "/../web/generate/";
        
        //Ajout d'une section
        $section = $phpWord->addSection();
        
        //Ajout d'un en-tête
        $phpWord->addFontStyle('eStyle', array('bold' => true, 'size' => 16));
        $phpWord->addFontStyle('rStyle', array('size' => 14));
        $header = $section->addHeader();
        $headerTable = $header->addTable();
        $headerTable->addRow();
        $headerTable->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5))->addImage('logo.png', array('height' => 80));
        $cellText = $headerTable->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(11));
        $cellText->addText(htmlspecialchars($rucher->getExploitation()->getNom()), 'eStyle', array('align' => 'right'));
        $cellText->addText(htmlspecialchars($rucher->getNom()), 'rStyle', array('align' => 'right'));
        
        //Ajout d'un pied de page
        $footer = $section->addFooter();
        $footer->addPreserveText(htmlspecialchars('{PAGE}/{NUMPAGES}'), null, array('align' => 'right'));
       
        //Création du style des cellules
        $cellStyle = array('valign' => 'center');
        $phpWord->addFontStyle('qStyle', array('size' => 12));
        
        //Ajout de la table contenant les qr codes
        $table = $section->addTable();
        
        //Nombre de ruches dans le fichier, utile pour créer une nouvelle ligne
        $nbRuches = 0;
        
        foreach( $ruches as $ruche){
                 //3 QRCodes par ligne
                 if (( $nbRuches % 3 ) === 0 ){
                     $table->addRow(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.5));
                 } 
                 $nbRuches++;
                 
                //Construction de l'url pour accéder à la ruche
                 $url = $this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $ruche->getId()), true);
                 
                //Construction du QRCode pointant sur l'url de la ruche
                $options = array(
                    'code'   => $url,
                    'type'   => 'qrcode',
                    'format' => 'png',
                );
                
                $barcode = $this->get('sgk_barcode.generator')->generate($options);  
                
                //Path du fichier avec le QRCode
                $filename = 'qrcode'.$ruche->getId().'.png';
                //Sauvegarde du fichier
                file_put_contents($path.$filename, base64_decode($barcode));
                
                //Ajout du QRCode dans le fichier ODT
                $cell = $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.33), $cellStyle);
                $cell->addText(htmlspecialchars($ruche->getNom()), 'qStyle', array('align' => 'center'));
                $cell->addImage(
                                'generate/'.$filename,
                                array(
                                    'width' => 151.18,
                                    'height' => 151.18,
                                    'wrappingStyle' => 'behind',
                                    'align' => 'center'
                                )
                         );
        }
        
        //Ajout de cellules vides si ligne incomplète
        $reste = 3 - $nbRuches % 3;
        if( $reste < 3 ){
            while ( $reste > 0 ){
                $cell = $table->addCell(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.33), $cellStyle);
                $reste--;
            }
        }
        
        //Sauvegarde du fichier ODT
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filename = $rucher->getId().'_qr_codes_rucher_'.$rucher->getNom().'.docx';
        $objWriter->save($path.$filename, 'Word2007', true);
        
        //Récupération du contenu du fichier
        $content = file_get_contents($path.$filename);        

        //Création de la réponse avec le contentu du fichier (pour le download)
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);
        $response->setContent($content);
        
        
        //Suppression des fichiers créés durant la création du fichier word
        unlink($path.$filename);  
        
        foreach( $ruches as $ruche){
                $filename = 'qrcode'.$ruche->getId().'.png';
                unlink($path.$filename);
        }
         
        //Retour de la réponse
        return $response;

    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function viewAction(Request $request, Rucher $rucher, $page)
    {        
        if( !$this->getUser()->canDisplayExploitation($rucher->getExploitation()) ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $apikey = $this->container->getParameter('apikey');
        
        $query = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Emplacement')->getListByRucher($rucher);    

        $paginator  = $this->get('knp_paginator');
        
        if( $rucher->getNumerotation() ){
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', $page),
                10,
                array(
                    'defaultSortFieldName' => 'e.numero',
                    'defaultSortDirection' => 'asc'
                )                
            );              
        }else{
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', $page),
                10,
                array(
                    'defaultSortFieldName' => 'ruche.nom',
                    'defaultSortDirection' => 'asc'
                )                
            );               
        }
      
        
        return $this->render('KGBeekeepingManagementBundle:Rucher:view.html.twig', 
                array(  'rucher'     => $rucher,
                        'apikey'     => $apikey,
                        'pagination' => $pagination
                    )
            );        
    }

    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}})  
    */    
    public function viewColoniesMortesAction(Request $request, Rucher $rucher, $page)
    {        
        if( !$this->getUser()->canDisplayExploitation($rucher->getExploitation()) ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $query = $this->getDoctrine()->getRepository('KGBeekeepingManagementBundle:Colonie')->getListMortesByRucher($rucher);    

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            10,
            array(
                'defaultSortFieldName' => 'colonie.numero',
            )                
        );        
        
        return $this->render('KGBeekeepingManagementBundle:Rucher:viewColoniesMortes.html.twig', 
                array(  'rucher'     => $rucher,
                        'pagination' => $pagination
                    )
            );        
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("rucher", options={"mapping": {"rucher_id" : "id"}}) 
    */    
    public function deleteAction(Rucher $rucher)
    {        
        if( !$this->getUser()->canDisplayExploitation($rucher->getExploitation()) || !$rucher->canBeDeleted() ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($rucher);
        $em->flush();
        
        $flash = $this->get('braincrafted_bootstrap.flash');
        $flash->success('Rucher supprimé avec succès');
        
        return $this->redirect($this->generateUrl('kg_beekeeping_management_home'));
    }
    
    /**
    * @Security("has_role('ROLE_USER')")
    * @ParamConverter("exploitation", options={"mapping": {"exploitation_id" : "id"}}) 
    */    
    public function addAction(Exploitation $exploitation, Request $request)
    {        
        if( !$this->getUser()->canDisplayExploitation($exploitation) ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $rucher = new Rucher($exploitation);
        $form = $this->createForm(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
                        
            $rucher->setExploitation($exploitation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Rucher créé avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_home'));
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
        if( !$this->getUser()->canDisplayExploitation($rucher->getExploitation()) ){
            throw new NotFoundHttpException('Page inexistante.');
        }
        
        $form = $this->createForm(new RucherType, $rucher);
        
        if ($form->handleRequest($request)->isValid()){
            
            $rucher->updateEmplacements();
            $em = $this->getDoctrine()->getManager();
            $em->persist($rucher);
            $em->flush();
        
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Rucher mis à jour avec succès');
        
            return $this->redirect($this->generateUrl('kg_beekeeping_management_view_rucher', array('rucher_id' => $rucher->getId())));     
        }
        return $this->render('KGBeekeepingManagementBundle:Rucher:update.html.twig', 
                             array('form'   => $form->createView(),
                                   'rucher' => $rucher 
                            ));
    }     
}    
    