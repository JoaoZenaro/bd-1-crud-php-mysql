<?php
require_once 'dados_acesso.php';
require_once 'utils.php';
mysqli_report(MYSQLI_REPORT_OFF);
// function conectarPDO()
// {
//     try {
//         $conn = new PDO(DSN . ':host=' . SERVIDOR . ';dbname=' . BANCODEDADOS, USUARIO, SENHA);
//         console_log('Conexão com PDO realizada com sucesso!');
//         return $conn;
//     } catch (PDOException $e) {
//         // echo '<h3>Erro: ' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1') . '</h3>';
//         echo '<h3>Erro: ' . $e->getMessage() . '</h3>';
//         exit();
//     }
// }
function conectarMySQLi_PD()
{
    $conn = @mysqli_connect(SERVIDOR, USUARIO, SENHA, BANCODEDADOS);
    if (!$conn) {
        // die('<h3>Erro: ' . mb_convert_encoding(mysqli_connect_error(), 'UTF-8', 'ISO-8859-1') . '</h3>');
        die('<h3>Erro: ' . mysqli_connect_error() . '</h3>');
    } else {
        console_log('Conexão com MySQLi Procedural realizada com sucesso!');
    }
    return $conn;
}
function conectarMySQLi_OO()
{
    $conn = @new mysqli(SERVIDOR, USUARIO, SENHA, BANCODEDADOS);
    if ($conn->connect_error) {
        // echo '<h3>Erro: ' . mb_convert_encoding($conn->connect_error, 'UTF-8', 'ISO-8859-1') . '</h3>';
        echo '<h3>Erro: ' . $conn->connect_error . '</h3>';
        exit();
    } else {
        console_log('Conexão com MySQLi Orientado a Objetos realizada com sucesso!');
    }
    return $conn;
}

function verificaBD($conn) {
    // Verifica no dicionário de dados do SGBD se o banco de dados existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . BANCODEDADOS . '"');
    if (!$stmt->fetchColumn()) {
        // Cria o banco se ele não existir
        $stmt = $conn->query('CREATE DATABASE IF NOT EXISTS ' . BANCODEDADOS);
    }
}

function verificaTabelaCurso($conn) {
    // Verifica se a tabela curso existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_SCHEMA = "' . BANCODEDADOS . '") AND (TABLE_NAME = "curso")');
    if (!$stmt->fetchColumn()) {
        // Cria a tabela 'curso' se ela não existir e a popula com alguns registros
        $stmt = $conn->query('CREATE TABLE IF NOT EXISTS curso (id_curso int AUTO_INCREMENT NOT NULL PRIMARY KEY, nome varchar(60) NOT NULL) ENGINE=InnoDB;');
        $stmt = $conn->query('INSERT INTO curso VALUES (null, "Ciência da Computação"), (null, "Engenharia de Computação"), (null, "Engenharia de Software"), (null, "Sistemas de Informação"), (null, "Design");');
    }
}

function verificaTabelaAluno($conn) {
    // Verifica se a tabela curso existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_SCHEMA = "' . BANCODEDADOS . '") AND (TABLE_NAME = "aluno")');
    if (!$stmt->fetchColumn()) {
        // Cria a tabela 'aluno' se ela não existir e a popula com alguns registros
        $stmt = $conn->query('CREATE TABLE IF NOT EXISTS aluno (id_aluno int NOT NULL AUTO_INCREMENT PRIMARY KEY, nome varchar(60) NOT NULL, nascimento date DEFAULT NULL, salario decimal(10,2) DEFAULT NULL, sexo enum("m", "f", "n") NOT NULL DEFAULT "n", ativo tinyint(1) NOT NULL DEFAULT "1", id_curso int DEFAULT NULL, foto longblob, FOREIGN KEY (id_curso) REFERENCES curso(id_curso)) ENGINE=InnoDB;');
        $foto = file_get_contents('default.png');
        $stmt = $conn->prepare('INSERT INTO aluno VALUES (null, "Fulano", "1990-10-25", 42.42, "n", 0, 1, :foto), (null, "Beltrano", "2000-01-01", 1234.56, "m", 1, 2, :foto);');
        $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
        $stmt->execute();
    }
}

function conectarPDO()
{
    try {
        // Realiza a conexão com o SGBD sem informar o banco de dados
        $conn = new PDO(DSN . ':host=' . SERVIDOR, USUARIO, SENHA);
        console_log('Conexão com PDO realizada com sucesso!');
        verificaBD($conn);
        // Abre uma conexão com o banco de dados
        $conn = new PDO(DSN . ':host=' . SERVIDOR . ';dbname=' . BANCODEDADOS, USUARIO, SENHA);
        verificaTabelaCurso($conn);
        verificaTabelaAluno($conn);
        return $conn;
    } catch (PDOException $e) {
        // echo '<h3>Erro: ' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1') . '</h3>';
        echo '<h3>Erro: ' . $e->getMessage() . '</h3>';
        exit();
    }
}