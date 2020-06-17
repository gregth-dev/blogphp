<?php

require '../vendor/autoload.php';

use App\Router;

define("DEBUG_TIME", microtime(true));
define('UPLOAD_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'uploads');
//librairie whoops pour débuger
$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if (isset($_GET['page']) && $_GET['page'] === '1') {
    //on réécrit l'url
    $uri = str_replace('page=1', '', $_SERVER['REQUEST_URI'][0]); //on conserve la partie sans 'page=1'
    $get = $_GET;
    unset($get['page']); //on retire page du tableau $_GET 
    $query = http_build_query($get);
    if (!empty($query)) {
        $uri = $uri . '?' . $query;
    }
    header('Location: ' . $uri);
    exit();
}


$router = new Router(dirname(__DIR__) . '/views');
//Pages vitrine du blog
$router->match('/', 'post/index', 'home')
    ->match('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->match('/blog/[*:slug]-[i:id]', 'post/show', 'post')
    //Pages Admin Posts
    ->match('/admin/post', 'admin/post/index', 'admin_post')
    ->match('/admin/post/[i:id]', 'admin/post/edit', 'edit_post')
    ->match('/admin/post/delete-[i:id]', 'admin/post/delete', 'delete_post')
    ->match('/admin/post/new', 'admin/post/new', 'new_post')
    //Pages Admin Categories
    ->match('/admin/category', 'admin/category/index', 'admin_category')
    ->match('/admin/category/[i:id]', 'admin/category/edit', 'edit_category')
    ->match('/admin/category/delete-[i:id]', 'admin/category/delete', 'delete_category')
    ->match('/admin/category/new', 'admin/category/new', 'new_category')
    //Pages Auth
    ->match('/auth', 'auth/login', 'login')
    ->match('/auth/logout', 'auth/logout', 'logout')
    ->run();
