<?php

namespace Backend;

class Middleware
{

    // Verifica usuário autenticado
    public function handle($request, $next)
    {

        session_start();

        if (!isset($_SESSION['session_usuario_id'])) {
            http_response_code(401); // Não autorizado
            header('Location: /login');
            exit();
        }

        return $next($request);
    }
}
