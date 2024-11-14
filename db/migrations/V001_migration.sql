-- Tabela usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    apelido VARCHAR(50),
    email VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela artigos
CREATE TABLE IF NOT EXISTS artigos (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    usuario_id INT(6) UNSIGNED NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela comentarios
CREATE TABLE IF NOT EXISTS comentarios (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conteudo TEXT NOT NULL,
    usuario_id INT(6) UNSIGNED NOT NULL,
    artigo_id INT(6) UNSIGNED NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (artigo_id) REFERENCES artigos(id)
);

-- Tabela imagens
CREATE TABLE IF NOT EXISTS imagens (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    caminho VARCHAR(255) NOT NULL,
    descricao VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de relacionamento artigo_imagens
CREATE TABLE IF NOT EXISTS artigo_imagens (
    artigo_id INT(6) UNSIGNED NOT NULL,
    imagem_id INT(6) UNSIGNED NOT NULL,
    PRIMARY KEY (artigo_id, imagem_id),
    FOREIGN KEY (artigo_id) REFERENCES artigos(id),
    FOREIGN KEY (imagem_id) REFERENCES imagens(id)
);
