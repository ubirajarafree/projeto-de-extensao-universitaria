DROP PROCEDURE IF EXISTS modifyComentariosUsuarioIdToApelido;
CREATE PROCEDURE modifyComentariosUsuarioIdToApelido()
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'comentarios' 
        AND COLUMN_NAME = 'usuario_apelido'
    ) THEN
        -- Adiciona a coluna usuario_apelido
        ALTER TABLE comentarios 
        ADD COLUMN usuario_apelido VARCHAR(50) NOT NULL AFTER usuario_id;

        -- Atualiza usuario_apelido a partir de usuario_id
        UPDATE comentarios 
        SET usuario_apelido = (SELECT apelido FROM usuarios WHERE usuarios.id = comentarios.usuario_id);

        -- Remove a coluna usuario_id
        ALTER TABLE comentarios 
        DROP COLUMN usuario_id;
    END IF;
END;

CALL modifyComentariosUsuarioIdToApelido();
