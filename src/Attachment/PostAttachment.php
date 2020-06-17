<?php

namespace App\Attachment;

use App\Model\Post;
use Intervention\Image\ImageManager;

class PostAttachment{

    public static function upload (Post $post,$data){
        $image = $post->getImage();
        if(isset($data['deleteImage'])){
            $directory = self::create_dir('posts');
            $oldFile = $directory . DIRECTORY_SEPARATOR . $post->getImage();
            if(file_exists($oldFile)){
                unlink($oldFile);
            }
        }
        if (empty($image) || $post->getPendingUpload() === false){
            return;
        }
        
        if (!empty($image)){
            $directory = self::create_dir('posts');
            if(!empty($post->getOldImage())){
                $oldFile = $directory . DIRECTORY_SEPARATOR . $post->getOldImage();
                if(file_exists($oldFile)){
                    unlink($oldFile);
                }
            }
            $filename = uniqid("",true) . '.jpg';
            /* $imgManager = new ImageManager(['driver' => 'gd']);
            $imgManager
            ->make($image)
            ->fit(350,200)
            ->save($directory . DIRECTORY_SEPARATOR . $filename); */
            move_uploaded_file($image, $directory . DIRECTORY_SEPARATOR . $filename );
            $post->setImage($filename);
        }
        
    }

    private static function create_dir($dir){
        $directory = UPLOAD_DIR.DIRECTORY_SEPARATOR.$dir;
            if (file_exists($directory)===false){
                mkdir($directory,0777,true);
            }
        return $directory;
    }

}