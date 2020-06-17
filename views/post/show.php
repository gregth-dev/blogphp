<?php

use App\Auth;
use App\Connection;
use App\Manager\CategoryManager;
use App\Manager\PostManager;


$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPdo();
$post = (new PostManager($pdo))->find('id',$id);
$category = (new CategoryManager($pdo))->hydratePosts([$post]);

if($post->getSlug() !== $slug){
    $url = $router->url('post',['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location :'.$url);
}




?>

<?php require 'media.php'; ?>

