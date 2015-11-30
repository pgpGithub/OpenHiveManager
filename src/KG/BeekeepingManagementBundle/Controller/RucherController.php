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
        $apiculteurExploitations = $rucher->getExploitation()->getApiculteurExploitations();
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
        
        //Création de l'objet phpWord pour le fichier ODT
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        //Création du path pour gérer les fichiers temporaires
        $path = $this->get('kernel')->getRootDir(). "/../web/generate/";
        
        $phpWord->loadTemplate('template_qrcodes.odt');
        
        //Ajout d'une section
        $section = $phpWord->addSection();

        //Ajout de la table contenant les qr codes
        $table = $section->addTable();
        
        //Nombre de ruches dans le fichier, utile pour créer une nouvelle ligne
        $nbRuches = 0;
        
        foreach( $rucher->getEmplacements() as $emplacement){
            if( $emplacement->getRuche() ){
                 //4 QRCodes par ligne
                 if (( $nbRuches % 4 ) === 0 ){
                     $table->addRow(900);
                 } 
                 $nbRuches++;
                 
                //Construction de l'url pour accéder à la ruche
                 $url = $this->generateUrl('kg_beekeeping_management_view_ruche', array('ruche_id' => $emplacement->getRuche()->getId()));
                 
                //Construction du QRCode pointant sur l'url de la ruche
                $options = array(
                    'code'   => $url,
                    'type'   => 'qrcode',
                    'format' => 'png',
                );
                
                $barcode = $this->get('sgk_barcode.generator')->generate($options);  
                
                //Path du fichier avec le QRCode
                $filename = 'qrcode'.$emplacement->getRuche()->getId().'.png';
                //Sauvegarde du fichier
                file_put_contents($path.$filename, base64_decode($barcode));
                
                //Ajout du QRCode dans le fichier ODT
                $table->addCell(2000)->addImage(
                                'generate/'.$filename,
                                array(
                                    'width' => 100,
                                    'height' => 100,
                                    'wrappingStyle' => 'behind'
                                )
                         );
            }
        }
        
        //Sauvegarde du fichier ODT
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
        $filename = 'qr_codes_rucher_'.$rucher->getNom().'_ID_'.$rucher->getId().'.odt';
        $objWriter->save($path.$filename, 'ODText', true);
        
        //Récupération du contenu du fichier
        $content = file_get_contents($path.$filename);        

        //Création de la réponse avec le contentu du fichier (pour le download)
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.text');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);
        $response->setContent($content);
        
        
        //Suppression des fichiers créés durant la création du fichier ODT
        unlink($path.$filename);  
        
        foreach( $rucher->getEmplacements() as $emplacement){
            if( $emplacement->getRuche() ){
                $filename = 'qrcode'.$emplacement->getRuche()->getId().'.png';
                unlink($path.$filename);
            }
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
        $apiculteurExploitations = $rucher->getExploitation()->getApiculteurExploitations();
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
        $apiculteurExploitations = $rucher->getExploitation()->getApiculteurExploitations();
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
        $exploitation = $rucher->getExploitation();
        $apiculteurExploitations = $exploitation->getApiculteurExploitations();
        $not_permitted = true;
        
        foreach ( $apiculteurExploitations as $apiculteurExploitation ){
            if( $apiculteurExploitation->getApiculteur()->getId() == $this->getUser()->getId() ){
                $not_permitted = false;
                break;
            }
        }
        
        if( !$not_permitted ){
            foreach ( $rucher->getEmplacements() as $emplacement){
                if( $emplacement->getRuche() || !$emplacement->getTranshumancesfrom()->isEmpty() || !$emplacement->getTranshumancesto()->isEmpty() ){
                    $not_permitted = true;
                    break;                
                }
            }
        }
        
        if( $not_permitted ){
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
