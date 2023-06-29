<?php
require_once 'services/conexoes.php';
require_once 'services/utils.php';

if (!isset($_GET["codigo_prd"])) header("Location: index.php");

$conn = conectarPDO();
$codigo_prd = $_GET["codigo_prd"];
$stmt = $conn->prepare('SELECT codigo_prd, descricao_prd, data_cadastro, preco, ativo, unidade, tipo_comissao, c.descricao_ctg, foto
                        FROM produtos p 
                        JOIN categorias c on p.codigo_ctg = c.codigo_ctg
                        WHERE p.codigo_prd = :codigo_prd');

$stmt->bindParam(':codigo_prd', $codigo_prd);
$stmt->execute();
$produto = $stmt->fetch();

if (!$produto) die('Falha no banco de dados!');

list($codigo_prd, $descricao_prd, $data_cadastro, $preco, $ativo, $unidade, $tipo_comissao, $descricao_ctg) = $produto;

$data_cadastro = date('d-m-Y', strtotime($produto['data_cadastro']));
$preco = number_format($produto['preco'], 2, ',', '.');
$tipo_comissoes = ['s' => 'Sem comissão', 'f' => 'Comissão fixa', 'p' => 'Percentual de comissão'];
$tipo_comissao = $tipo_comissoes[$produto['tipo_comissao']];
$ativo = $produto['ativo'] ? 'Sim' : 'Não';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Página de Detalhes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="container table-responsive" id="detalhes_produto">
    <h2>Detalhes do Produto</h2>
    <hr>
    <ul>
        <li class="imagem"><?php echo '<img src="data:image/png;base64,' . ($produto['foto'] ? base64_encode($produto['foto']) : '') . '" width="200px"/>'; ?></li>
        <li><b>Id: </b><?= $codigo_prd ?></li>
        <li><b>Descrição: </b><?= $descricao_prd ?></li>
        <li><b>Data Cadastro: </b><?= $data_cadastro ?></li>
        <li><b>Preço (R$): </b><?= $preco ?></li>
        <li><b>Ativo: </b><?= $ativo ?></li>
        <li><b>Unidade: </b><?= $unidade ?></li>
        <li><b>Tipo Comissão: </b><?= $tipo_comissao ?></li>
        <li><b>Categoria: </b><?= $descricao_ctg ?></li>
    </ul>
    <hr>
    <button type="button" onclick="window.history.back()" class="btn btn-outline-danger btn-lg">
        <i class="fas fa-door-open"></i> Voltar
    </button>
</div>;
</body>
</html>