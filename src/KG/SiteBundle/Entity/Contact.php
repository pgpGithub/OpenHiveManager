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

namespace KG\SiteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @var string
     * @Assert\Length(max=20, maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères") 
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s\’-]{1,29}$/",
     *     message="Nom incorrect"
     * )
     */
    private $nom;

    /**
     * @var string
     * @Assert\Length(max=20, maxMessage="Le prénom ne peut pas dépasser {{ limit }} caractères")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s\’-]{1,29}$/",
     *     message="Prénom incorrect"
     * )
     */    
    private $prenom;
    
    private $email;

    /**
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner le sujet du message")
     */
    private $sujet;

    /**
     * @var string
     * @Assert\Length(max=500, maxMessage="Le message ne peut pas dépasser {{ limit }} caractères")
     */       
    private $message;

    
    /**
     * @var boolean
     * @Assert\IsTrue(
     *     message = "Pour envoyer un message il faut être un humain")
     */
    private $secure = false;   
    
        
    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }    

    public function getPrenom()
    {
        return $this->nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }      

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }       
    
    public function getSujet()
    {
        return $this->sujet;
    }

    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }       
    
    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }       
    
    public function getSecure()
    {
        return $this->secure;
    }

    public function setSecure($secure)
    {
        $this->secure = $secure;
    }       
    
}