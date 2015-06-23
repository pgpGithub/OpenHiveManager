<?php

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Localisation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KG\BeekeepingManagementBundle\Entity\LocalisationRepository")
 */
class Localisation
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
     * @var float
     * @ORM\Column(name="longitude", type="decimal", precision=14, scale=8, nullable=true)
     * @Assert\NotBlank(message="Veuillez localiser votre rucher. Cette donnée est nécessaire à l'obtention des données météorologiques.")
     */
    private $longitude;

    /**
     * @var float
     * @ORM\Column(name="latitude", type="decimal", precision=14, scale=8, nullable=true)
     * @Assert\NotBlank(message="Veuillez localiser votre rucher. Cette donnée est nécessaire à l'obtention des données météorologiques.")
     */
    private $latitude;

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Rucher
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Rucher
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    public function setLatLng($latlng)
    {
        $this->setLatitude($latlng['lat']);
        $this->setLongitude($latlng['lng']);
        return $this;
    }
}