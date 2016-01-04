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
 * Visite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\VisiteRepository")
 */
class Visite
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
     * @var Colonie
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="visites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $colonie;    
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Activite")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activite;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\Tache", mappedBy="visite", cascade={"remove"})
     */
    private $taches;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="reine", type="boolean")
     */
    private $reine = false;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="pollen", type="boolean")
     */
    private $pollen = false;    

    /**
     * @var integer
     *
     * @ORM\Column(name="poids", type="decimal", precision=6, scale=3)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le poids de la ruche ne peut pas être négatif"
     * )
     */
    private $poids = 0;      
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nbcouvain", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de cadres de couvain ne peut pas être négatif"
     * )
     * @Assert\NotBlank(message="Veuillez indiquer le nombre de cadres de couvain présents dans la ruche")
     */
    private $nbcouvain;    

    /**
     * @var integer
     *
     * @ORM\Column(name="nbnourriture", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de cadres de nourriture ne peut pas être négatif"
     * )
     * @Assert\NotBlank(message="Veuillez indiquer le nombre de cadres de nourriture présents dans la ruche")
     */
    private $nbnourriture;  
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="celroyales", type="boolean")
     */
    private $celroyales = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Etat")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'état de la colonie")
     */
    private $etat;
    
    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Agressivite")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid() 
     * @Assert\NotBlank(message="Veuillez sélectionner l'agressivité de la colonie")
     */
    private $agressivite;

    /**
     * @var string
     *
     * @ORM\Column(name="nourrissement", type="text", length=100, nullable=true)  
     * @Assert\Length(max=100, maxMessage="Le type de nourrissement ne peut dépasser {{ limit }} caractères")
     */
    private $nourrissement;

    /**
     * @var string
     *
     * @ORM\Column(name="traitement", type="text", length=100, nullable=true)  
     * @Assert\Length(max=100, maxMessage="Le type de traitement ne peut dépasser {{ limit }} caractères")
     */
    private $traitement; 

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text", length=300, nullable=true)
     * @Assert\Length(max=300, maxMessage="Le champ observations ne peut dépasser {{ limit }} caractères") 
     */
    private $observations;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\HausseVisite", mappedBy="visite", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $hausses; 
    
    /**
     * Constructor
     */
    public function __construct(Colonie $colonie)
    {
        $this->colonie = $colonie;
        $this->setNbcouvain($colonie->getRuche()->getEmplacement()->getRuche()->getCorps()->getNbcouvain());
        $this->setNbnourriture($colonie->getRuche()->getEmplacement()->getRuche()->getCorps()->getNbnourriture());
        
        if( !$colonie->getVisites()->isEmpty() ){
            $lastVisite = $colonie->getVisites()->last();  
            $this->setEtat($lastVisite->getEtat());
            $this->setAgressivite($lastVisite->getAgressivite());
            $this->setPoids($lastVisite->getPoids());
        }
        
        $this->setDate(new \DateTime()); 
        $this->hausses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->taches = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($colonie->getRuche()->getHausses() as $hausse) {
            $this->addHauss(new HausseVisite($this, $hausse->getNbplein()));
        }
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
     * Set activite
     *
     * @param Activite $activite
     * @return Visite
     */
    public function setActivite(Activite $activite)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Visite
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie)
    {
        $this->colonie = $colonie;
        $colonie->addVisite($this);
        return $this;
    }

    /**
     * Get colonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonie()
    {
        return $this->colonie;
    }   

    /**
     * Set reine
     *
     * @param boolean $reine
     * @return Visite
     */
    public function setReine($reine)
    {
        $this->reine = $reine;

        return $this;
    }

    /**
     * Get reine
     *
     * @return boolean 
     */
    public function getReine()
    {
        return $this->reine;
    }

    /**
     * Set nourrissement
     *
     * @param string $nourrissement
     * @return Visite
     */
    public function setNourrissement($nourrissement)
    {
        $this->nourrissement = $nourrissement;

        return $this;
    }

    /**
     * Get nourrissement
     *
     * @return string 
     */
    public function getNourrissement()
    {
        return $this->nourrissement;
    }

    /**
     * Set traitement
     *
     * @param string $traitement
     * @return Visite
     */
    public function setTraitement($traitement)
    {
        $this->traitement = $traitement;

        return $this;
    }

    /**
     * Get traitement
     *
     * @return string 
     */
    public function getTraitement()
    {
        return $this->traitement;
    }

    /**
     * Set observations
     *
     * @param string $observations
     * @return Visite
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string 
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set etat
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Etat $etat
     * @return Visite
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
     * @return Visite
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
     * Set date
     *
     * @param \DateTime $date
     * @return Visite
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set pollen
     *
     * @param boolean $pollen
     * @return Visite
     */
    public function setPollen($pollen)
    {
        $this->pollen = $pollen;

        return $this;
    }

    /**
     * Get pollen
     *
     * @return boolean 
     */
    public function getPollen()
    {
        return $this->pollen;
    }

    /**
     * Set celroyales
     *
     * @param boolean $celroyales
     * @return Visite
     */
    public function setCelroyales($celroyales)
    {
        $this->celroyales = $celroyales;

        return $this;
    }

    /**
     * Get celroyales
     *
     * @return boolean 
     */
    public function getCelroyales()
    {
        return $this->celroyales;
    }

    /**
     * Set nbcouvain
     *
     * @param integer $nbcouvain
     * @return Visite
     */
    public function setNbcouvain($nbcouvain)
    {
        $this->nbcouvain = $nbcouvain;

        return $this;
    }

    /**
     * Get nbcouvain
     *
     * @return integer 
     */
    public function getNbcouvain()
    {
        return $this->nbcouvain;
    }

    /**
     * Set nbnourriture
     *
     * @param integer $nbnourriture
     * @return Visite
     */
    public function setNbnourriture($nbnourriture)
    {
        $this->nbnourriture = $nbnourriture;

        return $this;
    }

    /**
     * Get nbnourriture
     *
     * @return integer 
     */
    public function getNbnourriture()
    {
        return $this->nbnourriture;
    }
    
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        $nbcadrestotal = $this->nbcouvain + $this->nbnourriture;
        if ( $nbcadrestotal  > $this->getColonie()->getRuche()->getCorps()->getNbCadres()) {
            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de nourriture est plus grande que le nombre de cadres') 
                   ->atPath('nbnourriture')
                   ->addViolation();

            $context
                   ->buildViolation('La somme de cadres de couvain et de cadres de nourriture est plus grande que le nombre de cadres') 
                   ->atPath('nbcouvain')
                   ->addViolation();            
        }

        
        foreach( $this->getColonie()->getVisites() as $lastVisite ){
            if ( $this->date < $lastVisite->getDate()  && $lastVisite->getId() != $this->getId() ){                
                $context
                       ->buildViolation('La date ne peut pas être antérieur ou égale à celle d\'une ancienne visite') 
                       ->atPath('date')
                       ->addViolation();
            }            
        }
        
        if( $this->date < $this->getColonie()->getDateColonie() ){
            $context
                   ->buildViolation('La date ne peut pas être antérieur à celle de la naissance de la colonie') 
                   ->atPath('date')
                   ->addViolation();            
        }
        
        $today = new \DateTime();
        
        if( $this->date > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('date')
                   ->addViolation();            
        }
    }     

    /**
     * Add hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\HausseVisite $hausse
     * @return Visite
     */
    public function addHauss(\KG\BeekeepingManagementBundle\Entity\HausseVisite $hausse)
    {
        $this->hausses[] = $hausse;

        return $this;
    }

    /**
     * Remove hausses
     *
     * @param \KG\BeekeepingManagementBundle\Entity\HausseVisite $hausses
     */
    public function removeHauss(\KG\BeekeepingManagementBundle\Entity\HausseVisite $hausses)
    {
        $this->hausses->removeElement($hausses);
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
     * Set poids
     *
     * @param integer $poids
     * @return Visite
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids
     *
     * @return integer 
     */
    public function getPoids()
    {
        return $this->poids;
    }
    
    /**
     * Add taches
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Tache $tache
     * @return Ruche
     */
    public function addTache(\KG\BeekeepingManagementBundle\Entity\Tache $tache)
    {
        $this->taches[] = $tache;
        $tache->setVisite($this);
        return $this;
    }

    /**
     * Remove taches
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Tache $tache
     */
    public function removeTache(\KG\BeekeepingManagementBundle\Entity\Tache $tache)
    {
        $this->taches->removeElement($tache);
        $tache->setVisite();
        return $this;
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
}
