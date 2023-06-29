<?php
require_once 'dados_acesso.php';
require_once 'utils.php';

mysqli_report(MYSQLI_REPORT_OFF);

function verificaBD($conn) {
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . BANCODEDADOS . '"');
    if (!$stmt->fetchColumn()) $stmt = $conn->query('CREATE DATABASE IF NOT EXISTS ' . BANCODEDADOS);
}

function verificaTabelaCategoria($conn) {
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_SCHEMA = "' . BANCODEDADOS . '") AND (TABLE_NAME = "categorias")');
    if (!$stmt->fetchColumn()) {
        $stmt = $conn->query('CREATE TABLE IF NOT EXISTS categorias (codigo_ctg INT NOT NULL PRIMARY KEY, descricao_ctg VARCHAR(50) NOT NULL UNIQUE) ENGINE=InnoDB;');
        $stmt = $conn->query('INSERT INTO categorias VALUES (1,"Eletrônicos"), (2,"Roupas"), (3,"Acessórios"), (4,"Cosméticos");');
    }
}

function verificaTabelaProduto($conn) {
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_SCHEMA = "' . BANCODEDADOS . '") AND (TABLE_NAME = "produtos")');
    if (!$stmt->fetchColumn()) {
        $stmt = $conn->query('CREATE TABLE IF NOT EXISTS produtos (codigo_prd INT AUTO_INCREMENT PRIMARY KEY, descricao_prd VARCHAR(50) NOT NULL UNIQUE, data_cadastro DATE NOT NULL, preco DECIMAL(10, 2) NOT NULL DEFAULT 0.0, ativo BOOLEAN NOT NULL DEFAULT TRUE, unidade CHAR(5) DEFAULT "un", tipo_comissao ENUM("s", "f", "p") NOT NULL DEFAULT "s", codigo_ctg INT NOT NULL, foto LONGBLOB, FOREIGN KEY (codigo_ctg) REFERENCES categorias(codigo_ctg)) ENGINE=InnoDB;');
        $foto = file_get_contents('dal/default.png');
        $stmt = $conn->prepare('INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ("Smartphone", CURDATE(), 999.99, 1);');
        $stmt = $conn->prepare('INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ("Laptop", CURDATE(), 2999.99, 1);');
        $stmt = $conn->prepare('INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ("Camiseta", CURDATE(), 29.99, 2);');
        $stmt = $conn->prepare('INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ("Bolsa", CURDATE(), 49.99, 3);');
        $stmt = $conn->prepare('INSERT INTO produtos (descricao_prd, data_cadastro, preco, codigo_ctg) VALUES ("Luva", CURDATE(), 9.99, 3);');
        $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
        $stmt->execute();
    }
}

function conectarPDO()
{
    try {
        $conn = new PDO(DSN . ':host=' . SERVIDOR, USUARIO, SENHA);
        verificaBD($conn);
        $conn = new PDO(DSN . ':host=' . SERVIDOR . ';dbname=' . BANCODEDADOS, USUARIO, SENHA);
        verificaTabelaCategoria($conn);
        verificaTabelaProduto($conn);
        return $conn;

    } catch (PDOException $e) {
        echo '<h3>Erro: ' . $e->getMessage() . '</h3>';
        exit();
    }
}