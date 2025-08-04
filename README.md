--- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS setembro_amarelo DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE setembro_amarelo;

-- Criação da tabela de inscrições
CREATE TABLE IF NOT EXISTS inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nascimento DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    bairro VARCHAR(100),
    aluno_estacio VARCHAR(10),
    comorbidade VARCHAR(100),
    genero VARCHAR(20),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de usuários (admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção de um usuário admin (senha: 123456)
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$OhN1vOkAPOZpQY8q7DZfLuNT1Q9I6E4JrLwM12.iO6SU22xligMem');
