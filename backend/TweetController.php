<?php

namespace Backend;

use Backend\ResponseHelper as Response;
use Backend\Tweet;
use Backend\User;

class TweetController
{
    // Cria um novo tweet
    public function createTweet()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $tweet = new Tweet();

        // Validação simples
        if (empty($data['usuario_id']) || empty($data['conteudo'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Usuário e conteúdo são obrigatórios.']);
            return;
        }

        // Tenta criar o tweet e obter a resposta
        $result = $tweet->create($data);

        if ($result) {
            // Busca informações do usuário
            $usuario = $this->getUsuarioPorId($data['usuario_id']);

            if ($usuario) {
                http_response_code(201); // Criado
                echo json_encode([
                    'success' => true,
                    'message' => 'Tweet criado com sucesso.',
                    'tweet' => $result,
                    'usuario' => $usuario // Inclui informações do usuário
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
            }
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['success' => false, 'message' => 'Erro ao criar o tweet.']);
        }
    }

    // Busca todos os tweets com paginação
    public function getTweets($usuario_id, $page = 1, $limit = 10)
    {
        $tweet = new Tweet();
        $offset = ($page - 1) * $limit;

        $tweets = $tweet->findByUserId($usuario_id, $limit, $offset);

        if ($tweets) {
            http_response_code(200); // OK
            echo json_encode($tweets);
        } else {
            http_response_code(404); // Não encontrado
            echo json_encode(['message' => 'Nenhum tweet encontrado.']);
        }
    }

    // Busca todos os tweets com paginação
    public function getAllTweets($page = 1, $limit = 10)
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        $tweet = new Tweet();
        $offset = ($page - 1) * $limit;

        $tweets = $tweet->findAll($limit, $offset);

        if ($tweets) {
            http_response_code(200); // OK
            echo json_encode($tweets);
        } else {
            http_response_code(404); // Não encontrado
            echo json_encode(['message' => 'Nenhum tweet encontrado.']);
        }
    }

    // Busca todos os tweets com paginação
    public function getTweetsByUserId($usuarioId)
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        $tweet = new Tweet();
        $tweets = $tweet->findByUserId($usuarioId, $limit, ($page - 1) * $limit);

        if ($tweets) {
            http_response_code(200); // OK
            echo json_encode($tweets);
        } else {
            http_response_code(404); // Não encontrado
            echo json_encode(['message' => 'Nenhum tweet encontrado.']);
        }
    }

    // Busca todos os tweets com paginação
    public function getTweetsByApelido($usuarioApelido)
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        $tweet = new Tweet();
        $tweetsData = $tweet->findByApelido($usuarioApelido, $limit, ($page - 1) * $limit);

        $usuarioId = $_SESSION['session_usuario_id'];
        // Obtem informções do usuário
        $usuarioLogado = $this->getUsuarioPorId((int) $usuarioId);

        if ($tweetsData['tweets']) {
            http_response_code(200); // OK
            echo json_encode([
                'tweets' => $tweetsData['tweets'],
                'usuario' => $usuarioLogado, // Inclui informações do usuário
                'totalCount' => $tweetsData['totalCount'], // Inclui a contagem total
            ]);
        } else {
            http_response_code(404); // Não encontrado
            echo json_encode(['message' => 'Nenhum tweet encontrado.', 'usuario' => $usuarioLogado]);
        }
    }

    // Obtem informações do usuário pelo ID
    private function getUsuarioPorId($usuarioId)
    {
        $user = new User();
        return $user->findById($usuarioId);
    }

    // Deleta um tweet específico
    public function deleteTweet($tweetId, $usuarioId)
    {
        $tweet = new Tweet();
        $existingTweet = $tweet->findById($tweetId);

        // Verifica se o tweet existe
        if (!$existingTweet) {
            http_response_code(404); // Não encontrado
            echo json_encode(['message' => 'Tweet não encontrado.']);
            return;
        }

        // Verifica se o usuário logado é o dono do tweet
        $usuarioId = (int) $usuarioId; // Converte para inteiro (alternativa: condição menos estrita "!=")
        if ($existingTweet['usuario_id'] !== $usuarioId) {
            http_response_code(403); // Proibido
            echo json_encode(['message' => 'Você não tem permissão para deletar este tweet.']);
            return;
        }

        if ($tweet->delete($tweetId)) {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Tweet deletado com sucesso.']);
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['message' => 'Erro ao deletar o tweet.']);
        }
    }
}
