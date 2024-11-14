-- Evita erros ao executar o script múltiplas vezes.
DROP PROCEDURE IF EXISTS changeConstraintsEmailToApelidoOnUsuarios;
CREATE PROCEDURE changeConstraintsEmailToApelidoOnUsuarios()
BEGIN
    IF EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.STATISTICS 
        WHERE TABLE_NAME = 'usuarios' 
        AND INDEX_NAME = 'email'
    ) THEN
        ALTER TABLE usuarios
        DROP INDEX email;
    END IF;

    IF EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'usuarios' 
        AND COLUMN_NAME = 'apelido'
    ) THEN
        ALTER TABLE usuarios
        MODIFY COLUMN apelido VARCHAR(50) NOT NULL,
        ADD UNIQUE (apelido);
    END IF;
END;

CALL changeConstraintsEmailToApelidoOnUsuarios();
