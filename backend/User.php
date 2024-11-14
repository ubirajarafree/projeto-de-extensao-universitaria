<?php

namespace Backend;

use PDO;
use PDOException;

class User extends Model
{

    protected $table = 'usuarios';

    // Cria um novo usuário
    public function create($data)
    {
        try {
            $sql = "
            INSERT INTO {$this->table} 
            (nome, apelido, email, senha, avatar, bio) 
            VALUES (:nome, :apelido, :email, :senha, :avatar, :bio)
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':apelido', $data['apelido']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':senha', password_hash($data['senha'], PASSWORD_BCRYPT));
            $stmt->bindParam(':avatar', $data['avatar']);
            $stmt->bindParam(':bio', $data['bio']);
            $result = $stmt->execute();
            if ($result) {
                //error_log('Usuário criado com sucesso.');
                return true;
            } else {
                //error_log('Erro ao executar a query: ' . implode(' ', $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log('Erro ao criar usuário: ' . $e->getMessage());
            return false;
        }
    }

    // Busca um usuário pelo email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca um usuário pelo apelido
    public function findByApelido($apelido)
    {
        $sql = "SELECT * FROM {$this->table} WHERE apelido = :apelido";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':apelido', $apelido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca um usuário pelo email ou apelido
    public function findByEmailOrApelido($email, $apelido)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email OR apelido = :apelido";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':apelido', $apelido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca um usuário pelo ID
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca usuários pelos IDs
    public function findByIds(array $ids)
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE id IN ($placeholders)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
