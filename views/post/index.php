<?php

use App\Auth;
use App\Connection;
use App\Manager\PostManager;


$title = "Mon Blog";
$pdo = Connection::getPdo();

//$postManager = new PostManager($pdo);
//[$posts, $paginated] = $postManager->findPaginated();

[$posts, $paginated] = (new PostManager($pdo))->findPaginated();

?>

<h1>Mon Blog</h1>

<div class="row">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-4">
            <?php require 'card.php' ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="div d-flex justify-content-between my-4">
    <?= $paginated->previousLink($router->url('home')) ?>
    <?= $paginated->nextLink($router->url('home')) ?>
</div>