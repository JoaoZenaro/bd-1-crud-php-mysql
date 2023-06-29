-- Roteiro
USE unoesc;

CREATE TABLE aluno (
    id_aluno INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(60) NOT NULL,
    nascimento DATE,
    salario DECIMAL(10, 2)
) ENGINE=InnoDB;


INSERT INTO aluno (nome, nascimento, salario) VALUES ('Fulano', '1990-10-25', 1234.56);
INSERT INTO aluno (nome, nascimento, salario) VALUES ('Beltrano', '2005-09-30', 42.42);


ALTER TABLE aluno ADD COLUMN foto LONGBLOB NULL AFTER salario;

CREATE TABLE curso (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

INSERT INTO curso VALUES (null, 'Ciência da Computação'),
                        (null, 'Engenharia de Software'),
                        (null, 'Sistemas de Informação'),
                        (null, 'Design');


ALTER TABLE aluno ADD COLUMN id_curso INT AFTER salario;

UPDATE aluno SET id_curso = 1 WHERE id_aluno = 1;
UPDATE aluno SET id_curso = 2 WHERE id_aluno = 2;

ALTER TABLE aluno ADD FOREIGN KEY (id_curso) REFERENCES curso (id_curso);
ALTER TABLE aluno ADD COLUMN ativo BOOLEAN NOT NULL DEFAULT true AFTER salario;
ALTER TABLE aluno ADD COLUMN sexo ENUM('m', 'f', 'n') NOT NULL DEFAULT 'n' AFTER salario;

UPDATE aluno SET sexo = 'm' WHERE id_aluno = 1;
UPDATE aluno SET ativo = false WHERE id_aluno = 2;

-- Atividade
USE crud_produtos;

CREATE TABLE IF NOT EXISTS categorias (
    codigo_ctg INT NOT NULL PRIMARY KEY,
    descricao_ctg VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS produtos (
    codigo_prd INT AUTO_INCREMENT PRIMARY KEY,
    descricao_prd VARCHAR(50) NOT NULL UNIQUE,
    data_cadastro DATE NOT NULL,
    preco DECIMAL(10, 2) NOT NULL DEFAULT 0.0,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    unidade CHAR(5) DEFAULT 'un',
    tipo_comissao ENUM('s', 'f', 'p') NOT NULL DEFAULT 's',
    codigo_ctg INT NOT NULL,
    foto LONGBLOB,
    FOREIGN KEY (codigo_ctg) REFERENCES categorias(codigo_ctg)
);

INSERT INTO categorias VALUES (1,'Eletrônicos'), (2,'Roupas'), (3,'Acessórios'), (4,'Cosméticos');

INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ('Smartphone', CURDATE(), 999.99, 1);
INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ('Laptop', CURDATE(), 2999.99, 1);
INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ('Camiseta', CURDATE(), 29.99, 2);
INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ('Bolsa', CURDATE(), 49.99, 3);
INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ('Luva', CURDATE(), 9.99, 3);