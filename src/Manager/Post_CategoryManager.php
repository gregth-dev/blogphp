<?php

namespace App\Manager;

/**
 * Class qui gère la partie Post de la table post_category
 */

class Post_CategoryManager extends Manager{

    protected $table = 'post';
    protected $className = Post::class;

    public function deletePostCategory(int $id): bool
    {
       return $this->pdo->exec('DELETE FROM post_category WHERE post_id = '.$id.'');

    }

}