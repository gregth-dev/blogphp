<?php

namespace App\Helpers;

class Objects{

    
    public static function hydrate($object,array $data, array $fields): void
    {
        
        foreach($fields as $field){
            if($field === 'image'){
                $data[$field]['name'] = str_replace('-','',$data[$field]['name']);
                $data[$field]['name'] = str_replace('_','',$data[$field]['name']);
                
            }
            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_',' ',$field)));
            $object->$method($data[$field]);
        }
    }


}