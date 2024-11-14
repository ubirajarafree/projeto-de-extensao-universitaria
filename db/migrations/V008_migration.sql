DROP PROCEDURE IF EXISTS createIndexesOnLikes;
CREATE PROCEDURE createIndexesOnLikes()
BEGIN
    IF NOT EXISTS (
        SELECT *
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_NAME = 'likes'
        AND INDEX_NAME = 'idx_tweet_id'
        OR INDEX_NAME = 'idx_usuario_id'
    ) THEN
        -- Índices para melhorar a performance das buscas
        CREATE INDEX idx_tweet_id ON likes(tweet_id);
        CREATE INDEX idx_usuario_id ON likes(usuario_id);
    END IF;
END;

CALL createIndexesOnLikes();
