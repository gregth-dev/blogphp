<?php

namespace App;

use PDO;

class Connection{

    public static function getPdo(): PDO{
        return new PDO("mysql:host=mysql-gregorythorel.alwaysdata.net;dbname=gregorythorel_blogv2;charset=utf8", '195870', '1981bobY', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}