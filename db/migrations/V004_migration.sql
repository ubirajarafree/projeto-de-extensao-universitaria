-- Evita erros ao executar o script múltiplas vezes.
DROP PROCEDURE IF EXISTS AddAvatarToUsuarios;
CREATE PROCEDURE AddAvatarToUsuarios()
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'usuarios' 
        AND COLUMN_NAME = 'avatar'
    ) THEN
        ALTER TABLE usuarios
        ADD COLUMN avatar VARCHAR(255) DEFAULT 'default.png';
    END IF;
END;

CALL AddAvatarToUsuarios();
