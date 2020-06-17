<?php

namespace App;

use AltoRouter;
use App\Security\ForbiddenException;
use Faker\Factory;

class Router{

    private $viewPath;

    /* 
    Variable de type altorouter
    */
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null)
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    public function post(string $url, string $view, ?string $name = null)
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    public function match(string $url, string $view, ?string $name = null)
    {
        $this->router->map('POST|GET', $url, $view, $name);
        return $this;
    }

    public function url(string $name, array $params = []){
        return $this->router->generate($name,$params);
    }

    public function run()
    {
        $match = $this->router->match();
        $view = $match['target'] ?: 'e404';
        $faker = Factory::create('fr_FR');
        $params = $match['params'];
        $router = $this;
        $isAdmin = strpos($view, 'admin') !== false;
        $layout = $isAdmin ? 'admin/layout/default' : '/layout/default';
        try{
            ob_start();
            require $this->viewPath.DIRECTORY_SEPARATOR.$view. '.php';
            $content = ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . $layout. '.php';
        }catch(ForbiddenException $e){
            header('Location:'.$this->url('login') . '?error=1');
            exit();
        }
        return $this;
    }

}