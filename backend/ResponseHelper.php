<?php

namespace Backend;

class ResponseHelper
{

    // Calcula o tamanho do arquivo em bytes
    public static function sendResponse($filePath)
    {

        ob_start();
        require_once $filePath;
        $content = ob_get_clean();

        header('Content-Length: ' . strlen($content));
        echo $content;
    }
}
