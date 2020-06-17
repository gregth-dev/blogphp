<?php

namespace App\Manager\Exception;

use Exception;

class NotFoundException extends Exception{

    public function __construct(string $table, $attributs, $field)
    {
       $this->message = "Aucun enregistrement #$attributs# de ne correspond au champ $field dans la table $table"; 
    }


}