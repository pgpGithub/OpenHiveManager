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

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Ruche
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Ruche
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=25)
     */
    private $nom;

     /**
      * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher", inversedBy="ruches")
      * @ORM\JoinColumn(nullable=false)
      */
    private $rucher;  
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Image", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $image;

     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", mappedBy="ruche", cascade={"remove"}, orphanRemoval=true)
     */
    private $colonie;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", inversedBy="ruche", cascade={"persist"})
     */
    private $emplacement;
    
    /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Corps", inversedBy="ruche", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $corps;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\HausseRuche", mappedBy="ruche", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $hausses; 
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Matiere")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner la matière de la ruche")
     */
    private $matiere;    

    /**
     * Constructor
     */
    public function __construct(Emplacement $emplacement = null)
    {
        $this->hausses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setCorps(new Corps());
        if( $emplacement){
            $this->setEmplacement($emplacement);
            $this->setRucher($emplacement->getRucher());
        }
        $this->colonie = new Colonie($this);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Ruche
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set image
     *
     * @param Image $image
     * @return Ruche
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image 
     */
    public function getImage()
    {
        return $this->image;
    }    
    
    /**
     * Set Colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Ruche
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie = null)
    {
        $this->colonie = $colonie;

        return $this;
    }

    /**
     * Get Colonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonie()
    {
        return $this->colonie;
    }   

    /**
     * Set emplacement
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacement
     * @return Ruche
     */
    public function setEmplacement(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacement = null)
    {
        $this->emplacement = $emplacement;
        if($emplacement){
            $emplacement->setRuche($this);
            $this->rucher = $emplacement->getRucher();
        }
        return $this;
    }

    /**
     * Get emplacement
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Emplacement 
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }
    
    /**
     * Set corps
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Corps $corps
     * @return Ruche
     */
    public function setCorps(\KG\BeekeepingManagementBundle\Entity\Corps $corps)
    {
        $this->corps = $corps;
        $corps->setRuche($this);
        return $this;
    }

    /**
     * Get corps
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Corps 
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Add hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\HausseRuche $hausse
     * @return Ruche
     */
    public function addHauss(\KG\BeekeepingManagementBundle\Entity\HausseRuche $hausse)
    {       
        $this->hausses[] = $hausse;

        return $this;
    }

    /**
     * Remove hausse
     *
     * @param \KG\BeekeepingManagementBundle\Entity\HausseRuche $hausse
     */
    public function removeHauss(\KG\BeekeepingManagementBundle\Entity\HausseRuche $hausse)
    {
        $this->hausses->removeElement($hausse);
    }

    /**
     * Get hausses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHausses()
    {
        return $this->hausses;
    }

    /**
     * Set matiere
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Matiere $matiere
     * @return Ruche
     */
    public function setMatiere(\KG\BeekeepingManagementBundle\Entity\Matiere $matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Matiere 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }    

    /**
     * Set rucher
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucher
     * @return Ruche
     */
    public function setRucher(\KG\BeekeepingManagementBundle\Entity\Rucher $rucher)
    {
        $this->rucher = $rucher;
        $rucher->addRuche($this);

        return $this;
    }

    /**
     * Get rucher
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Rucher 
     */
    public function getRucher()
    {
        return $this->rucher;
    }
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        $break = false;
        
        foreach( $this->getEmplacement()->getRucher()->getExploitation()->getRuchers() as $rucher ){
            foreach( $rucher->getEmplacements() as $emplacement ){
                if( $emplacement->getRuche() != $this && $emplacement->getRuche() ){
                    if( strtoupper($emplacement->getRuche()->getNom()) == strtoupper($this->nom) ){
                        $context
                            ->buildViolation('Une autre ruche porte déjà ce nom dans le rucher '.$rucher->getNom()) 
                            ->atPath('nom')
                            ->addViolation();
                        $break = true;
                        break;
                    }
                }
            }
            if( $break ){
                break;
            }
        }
    }     
}
