-- Evita erros ao executar o script múltiplas vezes.
DROP PROCEDURE IF EXISTS AddBioToUsuarios;
CREATE PROCEDURE AddBioToUsuarios()
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'usuarios' 
        AND COLUMN_NAME = 'bio'
    ) THEN
        ALTER TABLE usuarios
        ADD COLUMN bio VARCHAR(255) DEFAULT '#bio';
    END IF;
END;

CALL AddBioToUsuarios();
