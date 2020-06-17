<?php

namespace App\Manager;

use App\Model\User;

class UserManager extends Manager{

    protected $table = 'user';
    protected $className = User::class;

    public function findByUserName (string $username) {
       return $this->find('username',$username);
    }
    
}