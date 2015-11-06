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

namespace KG\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Doctrine\UserManager;
use KG\UserBundle\Entity\User as User;

class UserController extends Controller
{
    /**
    * @Security("has_role('ROLE_USER')") 
    */        
    public function advertDeleteUserAction() {
        return $this->render('KGUserBundle:Profile:advert_delete.html.twig');
    }   
    
    /**
    * @Security("has_role('ROLE_USER')") 
    */        
    public function deleteUserAction() {
       
        $em = $this->getDoctrine()->getManager();
                                
        foreach( $this->getUser()->getApiculteurExploitations() as $apiExpl ){
            // Si l'utilisateur est responsable, on supprime l'exploitation
            if( $apiExpl->getResponsable() == $this->getUser() ){
                $em->remove($apiExpl->getExploitation());
            }
        }

        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->deleteUser($this->getUser());
        
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'Le compte de l\'utilisateur ' . $this->getUser()->getUsername() . ' a été intégralement supprimé');
        return $this->redirect($this->generateUrl('kg_site_home'));
    }
}