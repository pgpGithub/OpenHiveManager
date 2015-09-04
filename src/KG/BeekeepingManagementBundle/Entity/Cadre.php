<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cadre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\CadreRepository")
 */
class Cadre
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
     * @ORM\Column(name="couvain", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Le pourcentage de couvain ne peut pas être négatif",
     *      maxMessage = "Le pourcentage de couvain ne peut pas dépasser 100%"
     * )
     */
    private $couvain = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="pollen", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Le pourcentage de pollen ne peut pas être négatif",
     *      maxMessage = "Le pourcentage de pollen ne peut pas dépasser 100%"
     * )
     */
    private $pollen = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="miel", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Le pourcentage de miel ne peut pas être négatif",
     *      maxMessage = "Le pourcentage de miel ne peut pas dépasser 100%"
     * )
     */
    private $miel = 0;

    /**
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Ruche", inversedBy="cadres", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruche;
    
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
     * Set couvain
     *
     * @param integer $couvain
     * @return Cadre
     */
    public function setCouvain($couvain)
    {
        $this->couvain = $couvain;

        return $this;
    }

    /**
     * Get couvain
     *
     * @return integer 
     */
    public function getCouvain()
    {
        return $this->couvain;
    }

    /**
     * Set pollen
     *
     * @param integer $pollen
     * @return Cadre
     */
    public function setPollen($pollen)
    {
        $this->pollen = $pollen;

        return $this;
    }

    /**
     * Get pollen
     *
     * @return integer 
     */
    public function getPollen()
    {
        return $this->pollen;
    }

    /**
     * Set miel
     *
     * @param integer $miel
     * @return Cadre
     */
    public function setMiel($miel)
    {
        $this->miel = $miel;

        return $this;
    }

    /**
     * Get miel
     *
     * @return integer 
     */
    public function getMiel()
    {
        return $this->miel;
    }

    /**
     * Set ruche
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Ruche $ruche
     * @return Cadre
     */
    public function setRuche(\KG\BeekeepingManagementBundle\Entity\Ruche $ruche)
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
}
