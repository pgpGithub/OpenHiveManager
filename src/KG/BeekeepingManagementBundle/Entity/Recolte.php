<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Recolte
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\RecolteRepository")
 */
class Recolte
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La quantité ne peut pas être négative"
     * )
     * @Assert\NotBlank(message="Veuillez indiquer la quantité récoltée")
     */
    private $quantite;       
}
