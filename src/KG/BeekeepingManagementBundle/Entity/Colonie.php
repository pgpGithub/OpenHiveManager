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

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Colonie
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Colonie
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
      * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher", inversedBy="colonies")
      * @ORM\JoinColumn(nullable=false)
      */
    private $rucher;       
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateColonie", type="datetime")
     * @Assert\NotBlank(message="Veuillez remplir la date de naissance de la colonie")
     * @Assert\DateTime()
     */
    private $dateColonie;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Affectation")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner l'affectation de la colonie")
     */
    private $affectation;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Origine")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner l'origine de la colonie")
     */
    private $origineColonie;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Etat")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner l'état de la colonie")
     */
    private $etat;
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Agressivite")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner l'agressivité de la colonie")
     */
    private $agressivite;
    
     /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Remerage", mappedBy="colonie", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $remerages;
    
     /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="coloniesFilles", cascade="persist")
     * @ORM\JoinColumn()
     */
    private $colonieMere;

     /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", mappedBy="colonieMere", cascade="persist")
     */
    private $coloniesFilles;
    
     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", mappedBy="colonie", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $ruche;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", mappedBy="colonie", cascade={"remove"}, orphanRemoval=true)
     */
    private $visites;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Transhumance", mappedBy="colonie", cascade={"remove"}, orphanRemoval=true)
     */
    private $transhumances;
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Recolte", mappedBy="colonie", cascade={"remove"}, orphanRemoval=true)
     */
    private $recoltes;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="morte", type="boolean")
     */
    private $morte = false;    

    /**
     * @var Cause
     * 
     * @ORM\ManyToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Cause")
     */
    private $causes;    

    /**
     * @var string
     *
     * @ORM\Column(name="autreCause", type="string", length=50, nullable=true)
     * @Assert\Length(max=50, maxMessage="La cause ne peut dépasser {{ limit }} caractères") 
     */
    private $autreCause;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->causes          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coloniesFilles  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visites         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->remerages       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->addRemerage(new Remerage(new Reine(), true));
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
     * Set affectation
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Affectation $affectation
     * @return Colonie
     */
    public function setAffectation(\KG\BeekeepingManagementBundle\Entity\Affectation $affectation)
    {
        $this->affectation = $affectation;

        return $this;
    }

    /**
     * Get affectation
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Affectation 
     */
    public function getAffectation()
    {
        return $this->affectation;
    }

    /**
     * Set colonieMere
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonieMere
     * @return Colonie
     */
    public function setColonieMere(\KG\BeekeepingManagementBundle\Entity\Colonie $colonieMere = null)
    {
        $this->colonieMere = $colonieMere;

        return $this;
    }

    /**
     * Get colonieMere
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonieMere()
    {
        return $this->colonieMere;
    }

    /**
     * Set anneeColonie
     *
     * @param \DateTime $anneeColonie
     * @return Colonie
     */
    public function setDateColonie($dateColonie)
    {
        $this->dateColonie = $dateColonie;

        return $this;
    }

    /**
     * Get dateColonie
     *
     * @return \DateTime 
     */
    public function getDateColonie()
    {
        return $this->dateColonie;
    }

    /**
     * Set etat
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Etat $etat
     * @return Colonie
     */
    public function setEtat(\KG\BeekeepingManagementBundle\Entity\Etat $etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Etat 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set agressivite
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Agressivite $agressivite
     * @return Colonie
     */
    public function setAgressivite(\KG\BeekeepingManagementBundle\Entity\Agressivite $agressivite)
    {
        $this->agressivite = $agressivite;

        return $this;
    }

    /**
     * Get agressivite
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Agressivite 
     */
    public function getAgressivite()
    {
        return $this->agressivite;
    }       

    /**
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Colonie
     */
    public function setRuche(\KG\BeekeepingManagementBundle\Entity\Ruche $ruche = null)
    {
        $this->ruche = $ruche;

        return $this;
    }

    /**
     * Get ruche
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Ruche 
     */
    public function getRuche()
    {
        return $this->ruche;
    }

    /**
     * Add coloniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles
     * @return Colonie
     */
    public function addColoniesFilles(\KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles)
    {
        $this->coloniesFilles[] = $coloniesFilles;

        return $this;
    }

    /**
     * Remove coloniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles
     */
    public function removeColoniesFilles(\KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles)
    {
        $this->coloniesFilles->removeElement($coloniesFilles);
    }

    /**
     * Get coloniesFilles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColoniesFilles()
    {
        return $this->coloniesFilles;
    }

    /**
     * Set morte
     *
     * @param boolean $morte
     * @return Colonie
     */
    public function setMorte($morte)
    {
        $this->morte = $morte;

        return $this;
    }

    /**
     * Get morte
     *
     * @return boolean 
     */
    public function getMorte()
    {
        return $this->morte;
    }

    /**
     * Add cause
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Cause $cause
     * @return Colonie
     */
    public function addCause(\KG\BeekeepingManagementBundle\Entity\Cause $cause)
    {
        $this->causes[] = $cause;

        return $this;
    }

    /**
     * Remove cause
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Cause $cause
     */
    public function removeCause(\KG\BeekeepingManagementBundle\Entity\Cause $cause)
    {
        $this->causes->removeElement($cause);
    }

    /**
     * Get cause
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCauses()
    {
        return $this->causes;
    }

    /**
     * Set autreCause
     *
     * @param string $autreCause
     * @return Colonie
     */
    public function setAutreCause($autreCause)
    {
        $this->autreCause = $autreCause;

        return $this;
    }

    /**
     * Get autreCause
     *
     * @return string 
     */
    public function getAutreCause()
    {
        return $this->autreCause;
    }

    /**
     * Add visites
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Visite $visites
     * @return Ruche
     */
    public function addVisite(\KG\BeekeepingManagementBundle\Entity\Visite $visites)
    {
        $this->visites[] = $visites;

        return $this;
    }

    /**
     * Remove visites
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Visite $visites
     */
    public function removeVisite(\KG\BeekeepingManagementBundle\Entity\Visite $visites)
    {
        $this->visites->removeElement($visites);
    }

    /**
     * Get visites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVisites()
    {
        return $this->visites;
    }    

    /**
     * Set rucher
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucher
     * @return Colonie
     */
    public function setRucher(\KG\BeekeepingManagementBundle\Entity\Rucher $rucher)
    {
        $this->rucher = $rucher;

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
     * Set origineColonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Origine $origineColonie
     * @return Colonie
     */
    public function setOrigineColonie(\KG\BeekeepingManagementBundle\Entity\Origine $origineColonie)
    {
        $this->origineColonie = $origineColonie;

        return $this;
    }

    /**
     * Get origineColonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Origine 
     */
    public function getOrigineColonie()
    {
        return $this->origineColonie;
    }

    /**
     * Add coloniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles
     * @return Colonie
     */
    public function addColoniesFille(\KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles)
    {
        $this->coloniesFilles[] = $coloniesFilles;

        return $this;
    }

    /**
     * Remove coloniesFilles
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles
     */
    public function removeColoniesFille(\KG\BeekeepingManagementBundle\Entity\Colonie $coloniesFilles)
    {
        $this->coloniesFilles->removeElement($coloniesFilles);
    }  
    
    /**
     * diviser
     *
     * @param integer $nbnourriture
     * @param integer $nbcouvain
     * @return Colonie
     */
    public function diviser($nbnourriture, $nbcouvain)
    {
        $corps = $this->getRuche()->getCorps();
        
        $nbnourriture_div = $corps->getNbnourriture() - $nbnourriture; 
        $nbcouvain_div = $corps->getNbcouvain() - $nbcouvain;
       
        $corps->setNbnourriture($nbnourriture_div);
        $corps->setNbcouvain($nbcouvain_div);
        
        return $this;
    }
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        if( $this->colonieMere ){
            if( $this->colonieMere->getDateColonie() > $this->dateColonie ){
                 $context
                   ->buildViolation('La date de division ne peut pas être antérieur à la date de naissance de la colonie mère') 
                   ->atPath('dateColonie')
                   ->addViolation();  
            }
        }
        
        $today = new \DateTime();
        
        if( $this->dateColonie > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('dateColonie')
                   ->addViolation();            
        }
        
        // Si c'est le premier remérage (cas de la création d'une colonie)
        // l'écart entre la date de la colonie et l'année de la reine doit être < 5 ans
        if( $this->getRemerages()->count() == 1){
            if(  $this->getRemerages()[0]->getReine()->getAnneeReine()->diff($this->dateColonie)->format('%r%y') > 5 ){
                $context
                       ->buildViolation('L\'année de la colonie est trop éloignée de l\'année de la reine') 
                       ->atPath('dateColonie')
                       ->addViolation();                      
            }            
        }        
    }    

    /**
     * Add recolte
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Recolte $recolte
     * @return Colonie
     */
    public function addRecoltes(\KG\BeekeepingManagementBundle\Entity\Recolte $recolte)
    {
        $this->recoltesruche[] = $recolte;

        return $this;
    }

    /**
     * Remove recolte
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Recolte $recolte
     */
    public function removeRecoltes(\KG\BeekeepingManagementBundle\Entity\Recolte $recolte)
    {
        $this->recoltes->removeElement($recolte);
    }

    /**
     * Get recoltes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecoltes()
    {
        return $this->recoltes;
    }

    /**
     * Add transhumances
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumances
     * @return Colonie
     */
    public function addTranshumance(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumances)
    {
        $this->transhumances[] = $transhumances;

        return $this;
    }

    /**
     * Remove transhumances
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Transhumance $transhumances
     */
    public function removeTranshumance(\KG\BeekeepingManagementBundle\Entity\Transhumance $transhumances)
    {
        $this->transhumances->removeElement($transhumances);
    }

    /**
     * Get transhumances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranshumances()
    {
        return $this->transhumances;
    } 

    /**
     * Add remerages
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Remerage $remerages
     * @return Colonie
     */
    public function addRemerage(\KG\BeekeepingManagementBundle\Entity\Remerage $remerages)
    {
        $this->remerages[] = $remerages;
        $remerages->setColonie($this);
        return $this;
    }

    /**
     * Remove remerages
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Remerage $remerages
     */
    public function removeRemerage(\KG\BeekeepingManagementBundle\Entity\Remerage $remerages)
    {
        $this->remerages->removeElement($remerages);
    }

    /**
     * Get remerages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRemerages()
    {
        return $this->remerages;
    }
}
