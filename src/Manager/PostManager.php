<?php

namespace App\Manager;

use App\Model\Post;
use App\Pagineted;
use Exception;
use PDO;

final class PostManager extends Manager
{
    protected $table = 'post';
    protected $className = Post::class;

    public function findPaginated()
    {
        $pagineted = new Pagineted(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT count(id) FROM post",
            $this->pdo
        );
        $posts = $pagineted->getItems(Post::class);
        (new CategoryManager($this->pdo))->hydratePosts($posts);        
        return [$posts, $pagineted];
    }
    
    public function findPaginatedForCategory (int $categoryId){
        $pagineted = new Pagineted(
            "SELECT p.* 
            FROM post as p 
            JOIN post_category as pc ON pc.post_id = p.id
            WHERE pc.category_id = {$categoryId}
            ORDER BY created_at DESC",
            "SELECT count(category_id) 
            FROM post_category 
            WHERE category_id = {$categoryId}"  
        );
        $posts = $pagineted->getItems(Post::class);
        (new CategoryManager($this->pdo))->hydratePosts($posts);
        return [$posts, $pagineted];
    }

    
    public function update(Post $post): void
    {
        $req = $this->pdo->prepare("UPDATE {$this->table} SET NAME = :name, content = :content, slug = :slug, created_at = :created_at, image = :image WHERE id = :id");
        $req->bindValue(':id',$post->getiD(),PDO::PARAM_INT);
        $req->bindValue(':name',$post->getName(),PDO::PARAM_STR);
        $req->bindValue(':content',$post->getContent(),PDO::PARAM_STR);
        $req->bindValue(':slug',$post->getSlug(),PDO::PARAM_STR);
        $req->bindValue(':image',$post->getImage(),PDO::PARAM_STR);
        $req->bindValue(':created_at',$post->getCreatedAt()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
        $update = $req->execute();
        if ($update === false){
            throw new Exception("Impossible de modifier l'article");
        }
    }
    
    public function create(Post $post): void
    {
        $req = $this->pdo->prepare("INSERT INTO {$this->table} SET NAME = :name, content = :content, slug = :slug, created_at = :created_at, image = :image");
        $req->bindValue(':name',$post->getName(),PDO::PARAM_STR);
        $req->bindValue(':content',$post->postContent(),PDO::PARAM_STR);
        $req->bindValue(':slug',$post->getSlug(),PDO::PARAM_STR);
        $req->bindValue(':image',$post->getImage(),PDO::PARAM_STR);
        $req->bindValue(':created_at',$post->getcreatedAt()->format('Y-m-d H:i:s'),PDO::PARAM_STR);
        $create = $req->execute();
        if ($create === false){
            throw new Exception("Impossible d'ajouter l'article");
        }
        $post->setId((int)($this->pdo->lastInsertId()));
    }

    public function addPostCategory(int $id, array $categories): void{
        $query = $this->pdo->prepare("INSERT INTO post_category SET post_id = ?, category_id = ?");
        foreach($categories as $category){
            $query->execute([$id,$category]);
        }
    }

    
    
}
