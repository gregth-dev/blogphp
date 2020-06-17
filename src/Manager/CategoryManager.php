<?php

namespace App\Manager;

use App\Model\Category;
use App\Pagineted;
use PDO;

final class CategoryManager extends Manager 
{
    protected $table = 'category';
    protected $className = Category::class;

    /**
     * @param App\Model\Post[] $posts
     */
    public function hydratePosts (array $posts): void{
        $postsById = [];
        foreach ($posts as $post) {
            $post->setCategories([]);
            $postsById[$post->getId()] = $post;
        }

        $categories = $this->pdo
            ->query(
            'SELECT c.*, pc.post_id
            FROM post_category as pc
            JOIN category as c ON c.id = pc.category_id
            WHERE pc.post_id IN (' . implode(',', array_keys($postsById)) . ')'
            )
            ->fetchAll(PDO::FETCH_CLASS, $this->className);

        foreach ($categories as $category) {
            $postsById[$category->getPost_id()]->addCategory($category);
        }
    }

    public function findPaginated()
    {
        $pagineted = new Pagineted(
            "SELECT * FROM category ORDER BY id ASC",
            "SELECT count(id) FROM category",
            $this->pdo
        );
        $categories = $pagineted->getItems($this->className);
        (new CategoryManager($this->pdo))->hydratePosts($categories);        
        return [$categories, $pagineted];
    }
  
    public function update(Category $category): void
    {
    $req = $this->pdo->prepare("UPDATE {$this->table} SET NAME = :name, slug = :slug WHERE id = :id");
    $req->bindValue(':id',$category->getiD(),PDO::PARAM_INT);
    $req->bindValue(':name',$category->getName(),PDO::PARAM_STR);
    $req->bindValue(':slug',$category->getSlug(),PDO::PARAM_STR);
    $update = $req->execute();
        if ($update === false){
            throw new Exception("Impossible de modifier la catégorie");
        }
    }
    
    public function create(Category $category): void
    {
        $req = $this->pdo->prepare("INSERT INTO {$this->table} SET NAME = :name, slug = :slug");
        $req->bindValue(':name',$category->getName(),PDO::PARAM_STR);
        $req->bindValue(':slug',$category->getSlug(),PDO::PARAM_STR);
        $create = $req->execute();
            if ($create === false){
                throw new Exception("Impossible d'ajouter la catégorie");
            }
        $category->setId((int)($this->pdo->lastInsertId()));
    }

    public function list (): array{
        $categories = $this->readAll('name','asc');
        $results = [];
        foreach($categories as $category){
            $results[$category->getId()] = $category->getName();
        }
        return $results;
    }

   
}