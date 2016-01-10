<?php

namespace KG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
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
     * @Assert\Regex(
     *  pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}/",
     *  message="Pour votre sécurité, votre mot de passe doit contenir au moins 8 caractères, dont un chiffre, une majuscule et une minuscule."
     * )
     */
    protected $plainPassword;
    
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
    
  /**
   * Vérifie si l'utilisateur à le droit d'accéder à l'exploitation
   *
   * @param Exploitation $exploitation
   * @return bool
   */
  public function canDisplayExploitation(Exploitation $exploitation)
  {
    $permitted = false;
      
    $apiculteurExploitations = $exploitation->getApiculteurExploitations();  
    foreach ( $apiculteurExploitations as $apiculteurExploitation ){
        if( $apiculteurExploitation->getApiculteur()->getId() == $this->getId() ){
            $permitted = true;
            break;
        }
    }    
    
    return $permitted;
  }    
    
    
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        $username = strtolower( $this->getUsername() );
        $password = strtolower( $this->getPlainPassword() );
        
        if( strpos( $password, $username ) ){
            $context
                ->buildViolation('Pour votre sécurité, votre nom d\'utilisateur ne doit pas apparaître dans votre mot de passe')
                ->atPath('plainPassword')
                ->addViolation();  
        }
    }        
}
