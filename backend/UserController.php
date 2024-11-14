<?php

namespace Backend;

use Backend\ResponseHelper as Response;
use Backend\User;

class UserController
{

    // Exibe o formulário de registro
    public function showRegister()
    {
        Response::sendResponse(__DIR__ . '/../view_register.php');
    }

    // Exibe o formulário de login
    public function showLogin()
    {
        Response::sendResponse(__DIR__ . '/../view_login.php');
    }

    // Exibe a página de perfil
    public function showProfile()
    {
        session_start();
        Response::sendResponse(__DIR__ . '/../view_profile.php');
    }

    // Exibe a página de feed
    public function showFeed()
    {
        session_start();
        Response::sendResponse(__DIR__ . '/../view_feed.php');
    }

    // Verifica se o apelido já existe
    public function verifyApelido()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = new User();
        $existingUser = $user->findByApelido($data['apelido']);

        if ($existingUser) {
            http_response_code(409); // Conflito
            echo json_encode(['message' => 'Apelido já existe.']);
        } else {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Apelido disponível.']);
        }
    }


    // Cria um novo usuário
    public function createUser()
    {

        $data = json_decode(file_get_contents('php://input'), true);

        $user = new User();
        $existingUser = $user->findByApelido($data['apelido']);

        if ($existingUser) {
            http_response_code(409); // Conflito
            echo json_encode(['message' => 'Apelido já existe.']);
            return;
        }

        if ($user->create($data)) {
            http_response_code(201); // Criado
            echo json_encode(['message' => 'Cadastro realizado com sucesso.']);

            $currentUser = $user->findByApelido($data['apelido']);
            if ($currentUser) {
                // Verifique se o usuário já está logado
                if (isset($_SESSION['session_usuario_id'])) {
                    // Se estiver logado, destrói a sessão
                    session_unset();
                    session_destroy();
                }
                session_start();
                $_SESSION['session_usuario_id'] = $currentUser['id'];
                $_SESSION['session_usuario_apelido'] = $currentUser['apelido'];
            }
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['message' => 'Erro ao realizar o cadastro.']);
        }
    }

    // Realiza o login
    public function makeLogin()
    {

        $data = json_decode(file_get_contents('php://input'), true);

        $user = new User();
        $existingUser = $user->findByEmailOrApelido($data['email'], $data['apelido']);

        if (!$existingUser) {
            http_response_code(404); // Não encontrado
            echo json_encode(['message' => 'Usuário não encontrado.']);
            return;
        }

        if (password_verify($data['senha'], $existingUser['senha'])) {

            // Login realizado
            session_start();
            $_SESSION['session_usuario_id'] = $existingUser['id'];
            $_SESSION['session_usuario_apelido'] = $existingUser['apelido'];

            http_response_code(200); // Ok
            echo json_encode(['message' => 'Login realizado com sucesso.']);
        } else {
            http_response_code(401); // Não autorizado
            echo json_encode(['message' => 'Senha incorreta.']);
        }
    }

    // Realiza o logout
    public function makeLogout()
    {

        session_start();

        if (isset($_GET['exit']) && $_GET['exit'] === 'true') {
            session_unset();
            session_destroy();
            http_response_code(200);
            $this->showlogin();
        }
        session_unset();
        session_destroy();

        http_response_code(200);

        echo json_encode(['message' => 'Logout realizado com sucesso.']);
    }

}
