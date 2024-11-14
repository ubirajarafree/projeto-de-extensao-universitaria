-- "Upsert" usuário administrador
INSERT INTO usuarios (nome, apelido, email, senha, data_registro) VALUES
('Administrador', 'admin', 'admin@email.com', 'admin123', CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
nome = VALUES(nome), apelido = VALUES(apelido), senha = VALUES(senha), 
data_registro = VALUES(data_registro);
