<?php

namespace App\Security;

use Exception;

class ForbiddenException extends Exception{

    public function __construct()
    {
       $this->message = "Vous n'êtes pas identifié";
       
    }


}