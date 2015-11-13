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

/**
 * Localisation
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @Assert\NotBlank(message="Veuillez remplir la longitude")
     */
    private $longitude;

    /**
     * @var float
     * @ORM\Column(name="latitude", type="decimal", precision=14, scale=8, nullable=true)
     * @Assert\NotBlank(message="Veuillez remplir la latitude")
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