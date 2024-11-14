DROP PROCEDURE IF EXISTS ensureComentariosConstraints;
CREATE PROCEDURE ensureComentariosConstraints()
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM INFORMATION_SCHEMA.STATISTICS 
        WHERE TABLE_NAME = 'comentarios' 
        AND INDEX_NAME = 'unique_artigo_tweet_id'
    ) THEN
        -- Adiciona índice único para garantir que apenas um dos campos não seja nulo
        ALTER TABLE comentarios 
        ADD CONSTRAINT unique_artigo_tweet_id UNIQUE (artigo_id, tweet_id);
    END IF;
END;

CALL ensureComentariosConstraints();
