<?php

namespace Minivel;

class View
{
    public string $title = "";

    public function renderView($view, $args = []){
        $contentView = $this->renderContentView($view, $args);
        $layoutView = $this->renderLayoutView();
        return str_replace("{{content}}", $contentView, $layoutView);
    }

    protected function renderLayoutView(){
        $app = Application::$app;
        $layout = $app->layout;

        if($app->controller){
            $layout = $app->controller->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderContentView($viewFile, $args){
        foreach ($args as $key => $value){
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/{$viewFile}.php";
        return ob_get_clean();
    }
}