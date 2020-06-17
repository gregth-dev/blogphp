<?php

namespace App\Helpers;

class Text{

    public static function excerpt(string $content, int $limit = 60)
    {
        if(mb_strlen($content) >= $limit){
            $lastSpace = mb_strpos($content, ' ', $limit);
            return substr($content, 0, $lastSpace).'...';
        }
            
        return $content;
    }

    public static function secure(?string $data): ?string{
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);

        return $data;
    }


}