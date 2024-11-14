-- Tabela topicos
CREATE TABLE IF NOT EXISTS topicos (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT
);

-- Evita erros ao executar o script múltiplas vezes.
DROP PROCEDURE IF EXISTS AddTopicoIdToArtigos;
CREATE PROCEDURE AddTopicoIdToArtigos()
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'artigos' 
        AND COLUMN_NAME = 'topico_id'
    ) THEN
        ALTER TABLE artigos
        ADD COLUMN topico_id INT(6) UNSIGNED,
        ADD FOREIGN KEY (topico_id) REFERENCES topicos(id);
    END IF;
END;

CALL AddTopicoIdToArtigos();
