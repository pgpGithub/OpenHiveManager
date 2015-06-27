<?php

namespace KG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apiculteur
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"apiculteur" = "Apiculteur"})
 */
class Apiculteur extends User
{

}
