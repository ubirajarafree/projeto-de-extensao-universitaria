DROP PROCEDURE IF EXISTS modifyConteudoOnComentarios;
CREATE PROCEDURE modifyConteudoOnComentarios()
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.STATISTICS 
        WHERE TABLE_NAME = 'comentarios' 
        AND COLUMN_NAME = 'conteudo'
    ) THEN
        -- Altera coluna da tabela comentarios
        ALTER TABLE comentarios 
        MODIFY COLUMN conteudo VARCHAR(280) NOT NULL;
    END IF;
END;

CALL modifyConteudoOnComentarios();
