DROP PROCEDURE IF EXISTS addTweetIdToComentarios;
CREATE PROCEDURE addTweetIdToComentarios()
BEGIN
    -- Verifica a existência da coluna na tabela
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'comentarios' 
        AND COLUMN_NAME = 'tweet_id'
    ) THEN
        -- Altera tabela para incluir comentários de tweets
        ALTER TABLE comentarios 
        ADD COLUMN tweet_id INT(6) UNSIGNED DEFAULT NULL AFTER artigo_id,
        ADD CONSTRAINT fk_tweet_id FOREIGN KEY (tweet_id) REFERENCES tweets(id) ON DELETE CASCADE;
    END IF;
END;

CALL addTweetIdToComentarios();
