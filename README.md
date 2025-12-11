# Sistema Acadêmico - Gerenciamento de Alunos
==============================================

## Descrição do Projeto
Sistema completo de gerenciamento acadêmico desenvolvido em PHP com Bootstrap 5, contendo funcionalidades de autenticação de usuários, cadastro de alunos, visualização de dados e painel administrativo com gráficos estatísticos.

## Funcionalidades Principais
-  Autenticação e Usuários: Login/Logout seguro, cadastro de usuários, controle de acesso
-  Gestão de Alunos: Cadastro completo com validação, visualização organizada
-  Painel Administrativo: Dashboard interativo com gráficos estatísticos usando Chart.js

## Estrutura de Arquivos
- index.php - Página de login
- telacadastro.php - Página de registro de usuários
- painel.php - Dashboard principal
- formulario_aluno.php - Formulário de cadastro de alunos
- visualizar_alunos.php - Lista de todos os alunos
- login.php - Processamento de login
- cadastro.php - Processamento de registro
- cadastro_aluno.php - Processamento de cadastro de alunos
- logout.php - Encerramento de sessão
- verifica_login.php - Middleware de verificação
- conexao.php - Configuração da conexão MySQL
- navbar.php - Componente de navegação
- inserir_100_alunos.php - Script para dados de teste
- screenshots/ - Capturas de tela do sistema

## Estrutura do Banco de Dados

Tabela: users
-------------
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) UNIQUE NOT NULL,
    user_password VARCHAR(32) NOT NULL, -- MD5 hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Tabela: alunos
--------------
CREATE TABLE alunos (
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
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

## Consultas SQL Principais

1. Login de Usuário (login.php)
--------------------------------
SELECT user_id, user_email FROM users 
WHERE user_email = '$email' AND user_password = MD5('$senha')

2. Total de Alunos (painel.php)
--------------------------------
SELECT COUNT(*) as total FROM alunos

3. Últimos 5 Alunos (painel.php)
---------------------------------
SELECT * FROM alunos ORDER BY data_cadastro DESC LIMIT 5

4. Distribuição por Curso (painel.php)
--------------------------------------
SELECT curso, COUNT(*) as total FROM alunos 
GROUP BY curso ORDER BY total DESC

5. Evolução Mensal (painel.php)
--------------------------------
SELECT DATE_FORMAT(data_cadastro, '%Y-%m') as mes, COUNT(*) as total
FROM alunos GROUP BY DATE_FORMAT(data_cadastro, '%Y-%m')
ORDER BY mes DESC LIMIT 6

6. Tipos de Responsável (painel.php)
-------------------------------------
SELECT tipo_responsavel, COUNT(*) as total 
FROM alunos GROUP BY tipo_responsavel

7. Top 5 Bairros (painel.php)
-------------------------------
SELECT bairro, COUNT(*) as total FROM alunos 
GROUP BY bairro ORDER BY total DESC LIMIT 5

8. Faixa Etária (painel.php)
-----------------------------
SELECT 
    CASE
        WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) < 15 THEN 'Menos de 15'
        WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 15 AND 18 THEN '15-18 anos'
        WHEN TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 19 AND 25 THEN '19-25 anos'
        ELSE 'Acima de 25'
    END as faixa_etaria,
    COUNT(*) as total
FROM alunos
GROUP BY faixa_etaria
ORDER BY 
    CASE faixa_etaria
        WHEN 'Menos de 15' THEN 1
        WHEN '15-18 anos' THEN 2
        WHEN '19-25 anos' THEN 3
        ELSE 4
    END

9. Cadastros por Período (painel.php)
--------------------------------------
SELECT 
    CASE
        WHEN HOUR(data_cadastro) BETWEEN 6 AND 12 THEN 'Manhã'
        WHEN HOUR(data_cadastro) BETWEEN 12 AND 18 THEN 'Tarde'
        WHEN HOUR(data_cadastro) BETWEEN 18 AND 23 THEN 'Noite'
        ELSE 'Madrugada'
    END as periodo,
    COUNT(*) as total
FROM alunos
GROUP BY periodo
ORDER BY 
    CASE periodo
        WHEN 'Manhã' THEN 1
        WHEN 'Tarde' THEN 2
        WHEN 'Noite' THEN 3
        ELSE 4
    END

10. Verificação de Duplicidade (cadastro_aluno.php)
---------------------------------------------------
SELECT COUNT(*) as total FROM alunos 
WHERE nome_completo = '{$nome}' AND data_nascimento = '{$data_nascimento}'

## Design e Interface
- Tema de Cores: Roxo (#667eea) e Azul (#4299e1)
- Gradientes: Combinações suaves para cards e headers
- Ícones: Font Awesome para melhor experiência visual
- Layout Responsivo: Bootstrap 5 para responsividade
- Gráficos Interativos: Chart.js para visualização de dados

## Segurança
1. Validação de formulários: Todos os campos são validados
2. Proteção SQL Injection: mysqli_real_escape_string() e prepared statements
3. Controle de sessões: Verificação de login em páginas protegidas
4. Hash de senhas: MD5 (sugere-se migrar para password_hash() em produção)
5. Sanitização de output: htmlspecialchars() para prevenir XSS

### Passos de Instalação
1. Clone o repositório
2. Configure o banco de dados com as tabelas acima
3. Configure a conexão em conexao.php
4. Acesse via navegador: http://localhost/book/

## Scripts de Teste
O arquivo inserir_100_alunos.php insere 100 alunos fictícios com dados aleatórios para testar as funcionalidades do sistema.

