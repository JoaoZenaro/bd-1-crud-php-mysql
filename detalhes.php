<?php
require_once 'services/conexoes.php';
require_once 'services/utils.php';

if (!isset($_GET["id_aluno"])) {
    header("Location: consulta.php");
}

$conn = conectarPDO();
$idAluno = $_GET["id_aluno"];
$stmt = $conn->prepare('SELECT id_aluno, a.nome, nascimento, salario, sexo, ativo, c.nome AS nome_curso, foto 
                        FROM aluno a JOIN curso c ON a.id_curso=c.id_curso 
                        WHERE id_aluno = :id_aluno ');
$stmt->bindParam(':id_aluno', $idAluno);
$stmt->execute();
$aluno = $stmt->fetch();

if (!$aluno) {
    die('Falha no banco de dados!');
}

list($idAluno, $nome, $nascimento, $salario, $sexo, $ativo, $curso, $foto) = $aluno;
$nascimento = date('d/m/Y', strtotime($nascimento));
$salario = 'R$ ' . number_format($salario, 2, ',', '.');

$sexos = ['m' => 'Masculino', 'f' => 'Feminino', 'n' => 'Não informado'];
$sexo = $sexos[$aluno['sexo']];
$ativo = $aluno['ativo'] ? 'Sim' : 'Não';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Página de Detalhes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="container table-responsive" id="detalhes_aluno">
    <h2>Detalhes do Aluno</h2>
    <hr>
    <ul>
        <li class="imagem">
            <?php echo '<img src="data:image/png;base64,' . ($aluno['foto'] ? base64_encode($aluno['foto']) : '') . '" width="200px"/>'; ?>
            <!-- <?php echo '<img src="data:image/png;base64,' . base64_encode($foto) . '" width="200px"/>'; ?> -->
        </li>
        <li><b>Id: </b><?= $idAluno ?></li>
        <li><b>Nome: </b><?= $nome ?></li>
        <li><b>Nascimento: </b><?= $nascimento ?></li>
        <li><b>Salário: </b><?= $salario ?></li>
        <li><b>Sexo: </b><?= $sexo ?></li>
        <li><b>Ativo: </b><?= $ativo ?></li>
        <li><b>Curso: </b><?= $curso ?></li>
    </ul>
    <hr>
    <button type="button" onclick="window.history.back()" class="btn btn-outline-danger btn-lg">
        <i class="fas fa-door-open"></i>
        Voltar
    </button>
</div>;
</body>
</html>