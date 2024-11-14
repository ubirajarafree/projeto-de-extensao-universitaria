<?php

namespace Backend;

use PDO;
use PDOException;

class Tweet extends Model
{
    protected $table = 'tweets';

    // Cria um novo tweet
    public function create($data)
    {
        try {
            $sql = "
        INSERT INTO {$this->table} 
        (usuario_id, usuario_apelido, conteudo, data_criacao) 
        VALUES (:usuario_id, :usuario_apelido, :conteudo, NOW())
        ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $data['usuario_id']);
            $stmt->bindParam(':usuario_apelido', $data['usuario_apelido']);
            $stmt->bindParam(':conteudo', $data['conteudo']);
            $result = $stmt->execute();

            if ($result) {
                // Recupera o ID do tweet recém-criado
                $tweetId = $this->db->lastInsertId();

                // Consulta para obter o tweet completo
                $sql = "
            SELECT 
                tweets.id,
                tweets.conteudo,
                tweets.data_criacao,
                usuarios.nome AS usuario_nome, 
                usuarios.apelido AS usuario_apelido, 
                usuarios.avatar AS usuario_avatar 
            FROM 
                {$this->table} AS tweets
            JOIN 
                usuarios ON tweets.usuario_id = usuarios.id 
            WHERE 
                tweets.id = :id
            ";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $tweetId);
                $stmt->execute();

                $tweet = $stmt->fetch(PDO::FETCH_ASSOC);

                return $tweet; // Retornando o array do tweet
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Erro ao criar tweet: ' . $e->getMessage());
            return false;
        }
    }

    // Encontra todos os tweets de um usuário com suporte a paginação
    public function findByUserId($usuario_id, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id ORDER BY data_criacao DESC";

        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Encontra todos os tweets de um usuário com suporte a paginação
    public function findByApelido($usuario_apelido, $limit = null, $offset = null)
    {
        // Contar o total de tweets para o usuário específico
        $totalCountSql = "SELECT COUNT(*) FROM {$this->table} WHERE usuario_apelido = :usuario_apelido";
        $totalCountStmt = $this->db->prepare($totalCountSql);
        $totalCountStmt->bindParam(':usuario_apelido', $usuario_apelido);
        $totalCountStmt->execute();
        $totalCount = $totalCountStmt->fetchColumn();

        // Consulta para buscar os tweets do usuário
        $sql = "SELECT * FROM {$this->table} WHERE usuario_apelido = :usuario_apelido ORDER BY data_criacao DESC";

        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario_apelido', $usuario_apelido);

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        $tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'tweets' => $tweets,
            'totalCount' => $totalCount, // Retorna a contagem total de tweets
        ];
    }

    // Encontra todos os tweets de um usuário por ID ou Apelido com suporte a paginação
    public function findByUserIdOrApelido($usuario_id = null, $usuario_apelido = null, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1"; // 1=1 facilita a adição de condições

        if ($usuario_id) {
            $sql .= " AND usuario_id = :usuario_id";
        }

        if ($usuario_apelido) {
            $sql .= " AND usuario_apelido = :usuario_apelido";
        }

        $sql .= " ORDER BY data_criacao DESC";

        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        if ($usuario_id) {
            $stmt->bindParam(':usuario_id', $usuario_id);
        }

        if ($usuario_apelido) {
            $stmt->bindParam(':usuario_apelido', $usuario_apelido);
        }

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Encontra um tweet pelo ID
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Encontra todos os tweets com suporte a paginação
    public function findAll($limit = null, $offset = null)
    {
        // Conta o total de tweets
        $totalCountSql = "SELECT COUNT(*) FROM {$this->table}";
        $totalCountStmt = $this->db->prepare($totalCountSql);
        $totalCountStmt->execute();
        $totalCount = $totalCountStmt->fetchColumn();

        // Consulta para buscar os tweets com os dados dos usuários
        $sql = "
        SELECT 
            tweets.*, 
            usuarios.nome AS usuario_nome, 
            usuarios.apelido AS usuario_apelido, 
            usuarios.avatar AS usuario_avatar 
        FROM 
            {$this->table} 
        JOIN 
            usuarios ON tweets.usuario_id = usuarios.id 
        ORDER BY 
            tweets.data_criacao DESC
    ";

        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        $tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'tweets' => $tweets,
            'totalCount' => $totalCount, // Retorna a contagem total de tweets
        ];
    }

    // Deleta um tweet pelo ID
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao deletar tweet: ' . $e->getMessage());
            return false;
        }
    }
}
