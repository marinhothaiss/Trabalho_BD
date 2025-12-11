```sql
-- CRIAR BANCO DE DADOS E TABELAS DO SISTEMA ACADÊMICO

-- 1. CRIAR BANCO DE DADOS
CREATE DATABASE IF NOT EXISTS login CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE login;

-- 2. TABELA DE USUÁRIOS (LOGIN)
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) UNIQUE NOT NULL,
    user_password VARCHAR(32) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (user_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABELA DE ALUNOS
CREATE TABLE IF NOT EXISTS alunos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_completo VARCHAR(200) NOT NULL,
    data_nascimento DATE NOT NULL,
    rua VARCHAR(150) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    nome_responsavel VARCHAR(200) NOT NULL,
    tipo_responsavel VARCHAR(50) NOT NULL,
    curso VARCHAR(100) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nome (nome_completo),
    INDEX idx_curso (curso),
    INDEX idx_bairro (bairro),
    INDEX idx_data_nascimento (data_nascimento),
    INDEX idx_data_cadastro (data_cadastro),
    INDEX idx_tipo_responsavel (tipo_responsavel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. INSERIR USUÁRIO DE TESTE
-- Email: admin@teste.com
-- Senha: 123456
INSERT INTO users (user_name, user_email, user_password) 
VALUES ('Administrador', 'admin@teste.com', MD5('123456'));

-- 5. CRIAR ÍNDICES ADICIONAIS PARA PERFORMANCE
CREATE INDEX idx_alunos_curso_data ON alunos (curso, data_cadastro);
CREATE INDEX idx_alunos_bairro_data ON alunos (bairro, data_cadastro);
CREATE INDEX idx_alunos_tipo_data ON alunos (tipo_responsavel, data_cadastro);

-- FIM DO SCRIPT SQL
```