CREATE TABLE IF NOT EXISTS produtos (
    codigo_prd INT AUTO_INCREMENT PRIMARY KEY,
    descricao_prd VARCHAR(50) NOT NULL UNIQUE,
    data_cadastro DATE NOT NULL DEFAULT CURRENT_DATE,
    preco DECIMAL(10, 2) NOT NULL DEFAULT 0.0,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    unidade CHAR(5) DEFAULT 'un',
    tipo_comissao ENUM('s', 'f', 'p') NOT NULL DEFAULT 's',
    codigo_ctg INT NOT NULL,
    foto LONGBLOB,
    FOREIGN KEY (codigo_ctg) REFERENCES categorias(codigo_ctg)
);

CREATE TABLE IF NOT EXISTS categorias (
    codigo_ctg INT NOT NULL PRIMARY KEY,
    descricao_ctg VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO categorias (descricao_ctg) VALUES ('Eletrônicos');
INSERT INTO categorias (descricao_ctg) VALUES ('Roupas');
INSERT INTO categorias (descricao_ctg) VALUES ('Alimentos');
INSERT INTO categorias (descricao_ctg) VALUES ('Acessórios');
INSERT INTO categorias (descricao_ctg) VALUES ('Cosméticos');

INSERT INTO produtos (descricao_prd, preco, codigo_ctg) VALUES ('Smartphone', 999.99, 1);
INSERT INTO produtos (descricao_prd, preco, codigo_ctg) VALUES ('Camiseta', 29.99, 2);
INSERT INTO produtos (descricao_prd, preco, codigo_ctg) VALUES ('Bolsa', 49.99, 3);
INSERT INTO produtos (descricao_prd, preco, codigo_ctg) VALUES ('Batom', 9.99, 4);