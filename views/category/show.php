<?php

use App\Manager\CategoryManager;
use App\Connection;
use App\Manager\PostManager;


$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPdo();
$category = (new CategoryManager($pdo))->find('id',$id);

if($category->getSlug() !== $slug){
    $url = $router->url('category',['slug' => $category->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location :'.$url);
}
$title = "Catégorie {$category->getName()}";



[$posts, $paginated] = (new PostManager($pdo))->findPaginatedForCategory($category->getId());


?>

<h1>Catégorie - <?= $category->getName() ?></h1>

<div class="row">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__).'/post/card.php' ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="div d-flex justify-content-between my-4">
    <?= $paginated->previousLink($router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()])) ?>
    <?= $paginated->nextLink($router->url('category',['id' => $category->getId(), 'slug' => $category->getSlug()])) ?>
    
</div>
