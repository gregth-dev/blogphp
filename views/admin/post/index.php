<?php

use App\Auth;
use App\Connection;
use App\Manager\PostManager;

Auth::check();

$title = "Mon Blog - Admin - Posts";
$pdo = Connection::getPdo();

[$items, $paginated] = (new PostManager($pdo))->findPaginated();

?>

<h1>Mon Blog</h1>

<div class="row">
        <div class="col-md-12">
            <?php require 'listPosts.php' ?>
        </div>
</div>

<div class="div d-flex justify-content-between my-4">
    <?= $paginated->previousLink($router->url('admin_post')) ?>
    <?= $paginated->nextLink($router->url('admin_post')) ?>
</div>