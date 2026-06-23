-- ============================================================
-- banco.sql — FitControl SGPAV
-- Script de criação do banco de dados e tabelas
-- Programação Web — IFES
--
-- COMO USAR:
--   1. Abra o phpMyAdmin ou o terminal MySQL
--   2. Execute: mysql -u root -p < banco.sql
--      OU copie e cole no phpMyAdmin
-- ============================================================

-- Cria o banco de dados se ainda não existir
CREATE DATABASE IF NOT EXISTS fitcontrol
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco para usar
USE fitcontrol;

-- ══════════════════════════════════════════════
-- TABELA: usuarios
-- Armazena os dados de acesso ao sistema
-- ══════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS usuarios (
    id         INT          NOT NULL AUTO_INCREMENT,  -- Chave primária
    nome       VARCHAR(120) NOT NULL,                 -- Nome completo do usuário
    email      VARCHAR(160) NOT NULL UNIQUE,          -- E-mail único (usado no login)
    senha      VARCHAR(255) NOT NULL,                 -- Hash da senha (password_hash)
    ativo      TINYINT(1)   NOT NULL DEFAULT 1,       -- 1 = ativo, 0 = inativo
    criado_em  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ══════════════════════════════════════════════
-- TABELA: produtos
-- Catálogo de suplementos e perecíveis
-- ══════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS produtos (
    id          INT           NOT NULL AUTO_INCREMENT,
    nome        VARCHAR(200)  NOT NULL,
    categoria   VARCHAR(80)   NOT NULL,
    preco       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estoque     INT           NOT NULL DEFAULT 0,
    ativo       TINYINT(1)    NOT NULL DEFAULT 1,
    criado_em   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ══════════════════════════════════════════════
-- TABELA: lotes
-- Controle de validade por lote de produto
-- ══════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS lotes (
    id           INT      NOT NULL AUTO_INCREMENT,
    produto_id   INT      NOT NULL,                  -- FK → produtos.id
    codigo_lote  VARCHAR(60)  NOT NULL,
    quantidade   INT      NOT NULL DEFAULT 0,
    validade     DATE     NOT NULL,
    criado_em    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    -- Chave estrangeira: garante integridade referencial
    CONSTRAINT fk_lote_produto
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ══════════════════════════════════════════════
-- TABELA: clientes
-- Cadastro de clientes da loja
-- ══════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS clientes (
    id        INT          NOT NULL AUTO_INCREMENT,
    nome      VARCHAR(120) NOT NULL,
    email     VARCHAR(160)          UNIQUE,
    telefone  VARCHAR(20),
    cpf       VARCHAR(14)           UNIQUE,
    ativo     TINYINT(1)   NOT NULL DEFAULT 1,
    criado_em DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ══════════════════════════════════════════════
-- TABELA: vendas
-- Registro de vendas realizadas
-- ══════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS vendas (
    id           INT           NOT NULL AUTO_INCREMENT,
    cliente_id   INT,                                -- FK → clientes.id (pode ser venda sem cadastro)
    usuario_id   INT           NOT NULL,             -- FK → usuarios.id (quem realizou a venda)
    total        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    criado_em    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_venda_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_venda_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ══════════════════════════════════════════════
-- DADOS INICIAIS: usuário administrador padrão
--
-- Senha: 123456
-- Hash gerado com: password_hash('123456', PASSWORD_BCRYPT)
-- ══════════════════════════════════════════════
INSERT INTO usuarios (nome, email, senha, ativo)
VALUES (
    'Admin FitControl',
    'admin@fitcontrol.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- senha: 123456
    1
);

-- ══════════════════════════════════════════════
-- DADOS DE EXEMPLO: produtos
-- ══════════════════════════════════════════════
INSERT INTO produtos (nome, categoria, preco, estoque) VALUES
    ('Whey Protein 900g Chocolate',  'Proteínas',   129.90, 50),
    ('Creatina 300g',                'Creatinas',    79.90, 30),
    ('BCAA 2:1:1 120 caps',          'Aminoácidos',  49.90, 45),
    ('Pré-Treino Black Skull 300g',  'Pré-Treino',   89.90, 20),
    ('Vitamina C 1000mg 60 caps',    'Vitaminas',    24.90, 80);

-- Tabela de cupons
CREATE TABLE IF NOT EXISTS cupons (
    id           INT          NOT NULL AUTO_INCREMENT,
    codigo       VARCHAR(50)  NOT NULL UNIQUE,
    desconto     INT          NOT NULL,
    expiracao    DATE         NOT NULL,
    status       VARCHAR(20)  DEFAULT 'Ativo',
    produto_id   INT,
    criado_em    DATETIME     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de alertas
CREATE TABLE IF NOT EXISTS alertas (
    id           INT          NOT NULL AUTO_INCREMENT,
    lote_id      INT          NOT NULL,
    tipo         VARCHAR(20)  NOT NULL,
    mensagem     TEXT,
    data_criacao DATETIME     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (lote_id) REFERENCES lotes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;