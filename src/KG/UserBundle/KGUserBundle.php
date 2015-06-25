<?php

namespace KG\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KGUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }    
}
