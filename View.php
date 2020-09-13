<?php

namespace app\core;

class View
{
    public string $title = ""; 

    public function renderView($view,$params = [])
    {
        $content = $this->renderOnlyView($view,$params);
        $layout = $this->layoutContent();
        return str_replace('{{content}}',$content,$layout);
    }

    public function renderOnlyContent($view)
    {
        $layout = $this->layoutContent();
        return str_replace('{{content}}',$view,$layout);

    }

    protected function layoutContent()
    {
        $layout = Application::$app->layout;
        
        if(Application::$app->controller)
        {
            $layout = Application::$app->controller->layout;
        }
        ob_start();
        include_once Application::$ROOT."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view,$params)
    {
        extract($params);
        ob_start();
        include_once Application::$ROOT."/views/$view.php";
        return ob_get_clean();
    }

}


?>