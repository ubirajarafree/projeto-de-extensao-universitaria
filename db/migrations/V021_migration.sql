DROP PROCEDURE IF EXISTS modifyTweetsUsuarioIdToApelido;
CREATE PROCEDURE modifyTweetsUsuarioIdToApelido()
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'tweets' 
        AND COLUMN_NAME = 'usuario_apelido'
    ) THEN
        -- Adiciona a coluna usuario_apelido
        ALTER TABLE tweets 
        ADD COLUMN usuario_apelido VARCHAR(50) NOT NULL AFTER usuario_id;

        -- Atualiza usuario_apelido a partir de usuario_id
        UPDATE tweets 
        SET usuario_apelido = (SELECT apelido FROM usuarios WHERE usuarios.id = tweets.usuario_id);

        -- Remove a coluna usuario_id
        -- ALTER TABLE tweets 
        -- DROP COLUMN usuario_id;
    END IF;
END;

CALL modifyTweetsUsuarioIdToApelido();
