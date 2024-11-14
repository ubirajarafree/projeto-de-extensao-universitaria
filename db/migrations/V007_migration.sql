-- Tabela tweets
CREATE TABLE IF NOT EXISTS tweets (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT(6) UNSIGNED NOT NULL,
    conteudo VARCHAR(280) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
-- Tabela likes
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tweet_id INT NOT NULL,
    usuario_id INT(6) UNSIGNED NOT NULL,
    data_like TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tweet_id) REFERENCES tweets(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de relacionamento tweets_imagens
CREATE TABLE IF NOT EXISTS tweets_imagens (
    tweet_id INT(6) UNSIGNED NOT NULL,
    imagem_id INT(6) UNSIGNED NOT NULL,
    PRIMARY KEY (tweet_id, imagem_id),
    FOREIGN KEY (tweet_id) REFERENCES tweets(id),
    FOREIGN KEY (imagem_id) REFERENCES imagens(id);
);
