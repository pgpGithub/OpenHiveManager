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
 * @ORM\HasLifecycleCallbacks() 
 * @ORM\Entity()
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
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;         
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateColonie", type="datetime")
     * @Assert\NotBlank(message="Veuillez remplir la date de naissance de la colonie")
     * @Assert\DateTime()
     */
    private $dateColonie;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMort", type="datetime")
     * @Assert\DateTime()
     */
    private $dateMort;
    
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
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Remerage", mappedBy="colonie", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $remerages;
    
     /**
     * @ORM\OneToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", inversedBy="colonie", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     */
    private $ruche;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Visite", mappedBy="colonie", cascade={"remove"}, orphanRemoval=true)
     */
    private $visites;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Tache", mappedBy="colonie", cascade={"remove"}, orphanRemoval=true)
     */
    private $taches;
    
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
    public function __construct(Ruche $ruche = null)
    {
        $this->causes          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visites         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->remerages       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->transhumances   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recoltes        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->taches          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ruche           = $ruche;
        $this->dateMort        = new \DateTime();
        $this->remerer(true);
    }

    /**
    * @ORM\PrePersist
    */
    public function fillNumero()
    {
        $numero = 0;
        
        foreach ($this->ruche->getEmplacement()->getRucher()->getExploitation()->getRuchers() as $rucher) {
            foreach ($rucher->getRuches() as $ruche) {
                // Si la colonie a déjà été sauvegardée, on la comptabilise
                if( $ruche->getColonie()->getId() ){
                    $numero ++;
                }
            }
        }
        
        $this->numero = $numero + 1;        
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
     * Set anneeColonie
     *
     * @param \DateTime $dateColonie
     * @return Colonie
     */
    public function setDateColonie($dateColonie)
    {
        $this->dateColonie = $dateColonie;

        return $this;
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
     * Get dateMort
     *
     * @return \DateTime 
     */
    public function getDateMort()
    {
        return $this->dateMort;
    }

    /**
     * Set dateMort
     *
     * @param \DateTime $dateMort
     * @return Colonie
     */
    public function setDateMort($dateMort)
    {
        $this->dateMort = $dateMort;

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
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Colonie
     */
    public function setRuche(\KG\BeekeepingManagementBundle\Entity\Ruche $ruche = null)
    {
        $this->ruche = $ruche;
        if($ruche){
            $ruche->setColonie($this);
        }
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
     * diviser
     *
     * @param var $origine
     * @return Colonie
     */
    public function diviser($origine)
    {
        $reineMere  = $this->remerages->last()->getReine();
        
        $colonie = new Colonie();
        $colonie->setOrigineColonie($origine);
        
        $colonie->remerages->last()->getReine()->setRace($reineMere->getRace());
        $colonie->remerages->last()->getReine()->setReineMere($reineMere);
                
        return $colonie;
    }

    /**
     * essaimer
     *
     * @param var $origine
     * @return Colonie
     */
    public function essaimer($origine)
    {
        $reineMere  = $this->remerages->last()->getReine();
        
        $colonie = new Colonie();
        $colonie->setOrigineColonie($origine);
        
        $colonie->remerages->last()->getReine()->setRace($reineMere->getRace());
        $colonie->remerages->last()->getReine()->setReineMere($reineMere);
                
        return $colonie;
    }    
    
    /**
     * remerer
     *
     * @return Colonie
     */
    public function remerer($naturel = null)
    {
        // Première reine (création colonie ou division)
        if($this->remerages->isEmpty()){
            $reine = new Reine();
        }else{
            $reine = $this->remerages->last()->getReine()->remerer();
        }
        
        new Remerage($reine, $this, $naturel);
        
        return $this;
    }    
    
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        $reineMere = $this->remerages->last()->getReine()->getReineMere(); 
                
        if( $reineMere ){
            if( $reineMere->getRemerage()->getColonie()->getDateColonie() > $this->dateColonie ){
                 $context
                   ->buildViolation('La date de la division ne peut pas être antérieur à la date de naissance de la colonie mère') 
                   ->atPath('dateColonie')
                   ->addViolation();  
            }
        }
        else{
            // Si c'est le premier remérage (cas de la création d'une colonie mais pas d'une division)
            // l'écart entre la date de la colonie et l'année de la reine doit être < 5 ans
            if( $this->remerages->count() == 1 ){
                if( $this->remerages[0]->getReine()->getAnneeReine() && $this->getDateColonie()){
                    if(  $this->remerages[0]->getReine()->getAnneeReine()->diff($this->dateColonie)->format('%r%y') > 5 ){
                        $context
                               ->buildViolation('La date de naissance de la colonie est trop éloignée de l\'année de la reine') 
                               ->atPath('dateColonie')
                               ->addViolation();                      
                    }  
                }
            }             
        }
        
        $today = new \DateTime();
        
        if( $this->dateColonie > $today ){
            $context
                   ->buildViolation('La date de naissance de la colonie ne peut pas être située dans le futur') 
                   ->atPath('dateColonie')
                   ->addViolation();            
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
     * @param \KG\BeekeepingManagementBundle\Entity\Remerage $remerage
     * @return Colonie
     */
    public function addRemerage(\KG\BeekeepingManagementBundle\Entity\Remerage $remerage)
    {
        $this->remerages[] = $remerage;
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

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Colonie
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }
    
    /**
     * Add taches
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Tache $taches
     * @return Ruche
     */
    public function addTache(\KG\BeekeepingManagementBundle\Entity\Tache $taches)
    {
        $this->taches[] = $taches;

        return $this;
    }

    /**
     * Remove taches
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Tache $taches
     */
    public function removeTache(\KG\BeekeepingManagementBundle\Entity\Tache $taches)
    {
        $this->taches->removeElement($taches);
    }

    /**
     * Get taches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTaches()
    {
        return $this->taches;
    }   
    
    public function canBeDeleted()
    {
        $permitted = true;
        
        foreach ( $this->getRemerages() as $remerage ){
            if( !$remerage->getReine()->getReinesFilles()->isEmpty() ){
                $permitted = false;
                break;
            }
        }
        
        return $permitted;
    }
    
    public function canBeUpdated()
    {
        $permitted = true;
        
        if( $this->getMorte()){
            $permitted = false;
        }
        
        return $permitted;
    }
    
    public function canBeDivisee()
    {
        $permitted = true;
        
        if( $this->getMorte() || $this->getRuche()->getCorps()->getNbcouvain() < 2){
            $permitted = false;
        }
        
        return $permitted;
    }

    public function canBeEssaimee()
    {
        $permitted = true;
        
        if( $this->getMorte() ){
            $permitted = false;
        }
        
        return $permitted;
    }    
    
    public function canBeTuee()
    {
        $permitted = true;
        
        if( $this->getMorte()){
            $permitted = false;
        }
        
        return $permitted;        
    }

    public function canBeRemeree()
    {
        $permitted = true;
        
        if( $this->getMorte()){
            $permitted = false;
        }
        
        return $permitted;        
    }    
    
    public function canBeRecoltee()
    {
        $permitted = true;
        
        if( $this->getMorte()){
            $permitted = false;
        }
        elseif( $this->getRuche()->getHausses()->isEmpty() ){
            $permitted = false;
        }
        
        return $permitted;
    }  
    
    public function canHaveNewTache()
    {
        $permitted = true;
        
        if( $this->getMorte()){
            $permitted = false;
        }
        
        return $permitted;        
    }

    public function canHaveNewTranshumance()
    {
        $permitted = true;
        
        if( $this->getMorte()){
            $permitted = false;
        }
        
        return $permitted;        
    }
    
    public function canHaveNewVisite()
    {
        $permitted = true;
        
        if( $this->getMorte()){
            $permitted = false;
            return $permitted; 
        }

        $today = new \DateTime();
        $today->setTime('00', '00', '00');
        
        $lastVisite = $this->getVisites()->last();
        if ( $lastVisite ){
            if ( $lastVisite->getDate() >= $today ){
                $permitted = false;
            }
        }
        
        return $permitted;        
    }   
    
    public function hasFille(){
        
        $fille = false;
        
        foreach( $this->getRemerages() as $remerage ){
            if( !$remerage->getReine()->getReinesFilles()->isEmpty() ){
                $fille = true;
                break;
            }
        }  
            
        return $fille;
    }
}
