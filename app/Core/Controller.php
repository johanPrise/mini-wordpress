<?php
namespace App\Core;

class Controller
{
    protected function view(string $path, array $data = [], string $layout = "main")
    {
        extract($data);

        $viewFile = __DIR__ . "/../Views/" . $path . ".php";
        $layoutFile = __DIR__ . "/../Views/layouts/" . $layout . ".php";

        if (!file_exists($viewFile)) {
            die("Vue introuvable : $viewFile");
        }

        include $layoutFile;
    }
}
