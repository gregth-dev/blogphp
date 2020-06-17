<?php

use App\Auth;
use App\Connection;
use App\Manager\PostManager;

Auth::check();

$id=$params['id'];

$pdo = Connection::getPdo();
$manager = (new PostManager($pdo))->delete($id,'l\'article');
header('Location: '.$router->url('admin_post').'?delete=1');
?>
