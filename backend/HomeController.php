<?php

namespace Backend;

use Backend\ResponseHelper as Response;

class HomeController
{

    // Exibe a página inicial
    public function showHome()
    {
        Response::sendResponse(__DIR__ . '/../view_home.php');
    }
}
