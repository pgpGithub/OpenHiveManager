<?php

namespace KG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

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
     * @ORM\OneToMany(targetEntity="KG\BeekeepingManagementBundle\Entity\ApiculteurExploitation", mappedBy="apiculteur")
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
     * @param \KG\BeekeepingManagementBundle\Entity\ApiculteurExploitation $apiculteurExploitation
     * @return User
     */
    public function addApiculteurExploitations(\KG\BeekeepingManagementBundle\Entity\ApiculteurExploitation $apiculteurExploitation)
    {
        $this->apiculteurExploitations[] = $apiculteurExploitation;

        return $this;
    }

    /**
     * Remove apiculteurExploitations
     *
     * @param \KG\BeekeepingManagementBundle\Entity\ApiculteurExploitation $apiculteurExploitation
     */
    public function removeApiculteurExploitations(\KG\BeekeepingManagementBundle\Entity\ApiculteurExploitation $apiculteurExploitation)
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
}
