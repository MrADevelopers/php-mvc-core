<?php

namespace mradevelopers\phpmvc;

use mradevelopers\phpmvc\middlewares\AuthMiddleware;
use mradevelopers\phpmvc\middlewares\BaseMiddleware;

class Controller
{
    public String $layout = '_master';
    public String $action = '';

    protected array $middlewares = [];

    public function render($view,$params = [])
    {
        return Application::$app->view->renderView($view,$params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares()
    {
        return $this->middlewares;
    }

}


?>