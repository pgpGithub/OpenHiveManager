<?php

namespace KG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use KG\BeekeepingManagementBundle\Entity\Exploitation;
use KG\BeekeepingManagementBundle\Entity\ApiculteurExploitation;

/**
 * User
 *
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\ApiculteurExploitation", mappedBy="apiculteur", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $apiculteurExploitations;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiculteurExploitations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add apiculteurExploitations
     *
     * @param ApiculteurExploitation $apiculteurExploitation
     * @return User
     */
    public function addApiculteurExploitation(ApiculteurExploitation $apiculteurExploitation)
    {
        $this->apiculteurExploitations[] = $apiculteurExploitation;
        $apiculteurExploitation->setApiculteur($this);

        return $this;
    }

    /**
     * Remove apiculteurExploitations
     *
     * @param ApiculteurExploitation $apiculteurExploitation
     */
    public function removeApiculteurExploitation(ApiculteurExploitation $apiculteurExploitation)
    {
        $this->apiculteurExploitations->removeElement($apiculteurExploitation);
    }

    /**
     * Get apiculteurExploitations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApiculteurExploitations()
    {
        return $this->apiculteurExploitations;
    }

    /**
     * Get exploitations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExploitations()
    {  
        $exploitations = new \Doctrine\Common\Collections\ArrayCollection(); 
                
        foreach ( $this->apiculteurExploitations as $apiculteurExploitation ){
            $exploitations->add($apiculteurExploitation->getExploitation());
        }
        
        return $exploitations;
    }   
}
