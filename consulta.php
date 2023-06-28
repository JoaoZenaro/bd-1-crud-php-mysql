<?php
require_once "conexoes.php";
require_once 'utils.php';
$conn = conectarPDO();
$nome_pesquisa = $_GET['nome_pesquisa'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Listagem com Filtro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid" id="listagem_alunos">
        <form action="consulta.php" method="get">
            <div class="d-flex mt-2 p-3 bg-secondary">
                <div class="input-group col-10 busca">
                    <span class="input-group-text">
                        <i class="fa fa-search"></i>
                    </span>
                    <div class="form-floating">
                        <input id="filtro" type="search" name="nome_pesquisa" class="form-control" value="<?=$nome_pesquisa ?>" placeholder="Entre com o nome do aluno">
                        <label for="filtro" class="pt-2">Entre com o nome do aluno</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <div class="col-1"></div>
                    <button id="btnLimpar" type="button" class="btn btn-danger">Limpar</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption>Relação de Alunos</caption>
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Nascimento</th>
                        <th>Salário (R$)</th>
                        <th>Sexo</th>
                        <th>Ativo</th>
                        <th>Curso</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <?php
                $filtro = "%{$nome_pesquisa}%";
                $stmt = $conn->prepare('SELECT id_aluno, a.nome, nascimento, salario, sexo, ativo, a.id_curso, c.nome AS nome_curso, foto 
                                        FROM aluno a 
                                        JOIN curso c ON a.id_curso=c.id_curso 
                                        WHERE a.nome LIKE :nome_aluno ');
                $stmt->bindParam(':nome_aluno', $filtro, PDO::PARAM_STR);
                $stmt->execute();
                while ($aluno = $stmt->fetch()) {
                    $data_nascimento = date('d-m-Y', strtotime($aluno['nascimento']));
                    $salario = number_format($aluno['salario'], 2, ',', '.');

                    $sexos = ['m' => 'Masculino', 'f' => 'Feminino', 'n' => 'Não informado'];
                    
                    $sexo = $sexos[$aluno['sexo']];
                    $ativo = $aluno['ativo'] ? 'Sim' : 'Não';
                ?>
                    <tr>
                        <td style="width: 5%;"><?php echo $aluno['id_aluno'] ?></td>
                        <td style="width: 20%;">
                            <a href="detalhes.php?id_aluno=<?php echo $aluno['id_aluno'] ?>">
                                <?= $aluno['nome'] ?>
                            </a>
                        </td>
                        <td style="width: 10%;" class="text-center"><?= $data_nascimento ?></td>
                        <td style="width: 10%;" class="text-end"><?= $salario ?></td>
                        <td style="width: 15%;"><?= $aluno['sexo'] ?> - <?= $sexo ?></td>
                        <td style="width: 5%;"><?= $ativo ?></td>
                        <td style="width: 20%;"><?= $aluno['id_curso'] ?> - <?= $aluno['nome_curso'] ?></td>
                        <td style="width: 15%;" class="imagem">
                            <a href="detalhes.php?id_aluno=<?= $aluno['id_aluno'] ?>">
                                <?php echo '<img src="data:image/png;base64,' . ($aluno['foto'] ? base64_encode($aluno['foto']) : '') . '" width="200px"/>'; ?>
                                <!-- <?php echo '<img src="data:image/png;base64,' . base64_encode($aluno['foto']) . '" width="200px"/>'; ?> -->
                            </a>
                        </td>
                    </tr>

                <?php
                }
                ?>
                <tfoot>
                    <tr>
                        <td colspan="8" style="text-align: center">
                            Data atual: <?= retornarDataAtual() ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <ul>
                <h4>Lista de Cursos</h4>
                <?php
                    $stmt = $conn->query('SELECT * FROM curso', PDO::FETCH_OBJ);
                    while ($curso = $stmt->fetch()) {
                ?>
                <li>
                    ID: <strong><?= $curso->id_curso ?></strong> -
                    Nome: <strong><?= $curso->nome ?></strong>
                </li>
                <?php
                    }
                    $stmt = null;
                    $conn = null;
                ?>
            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#btnLimpar").on("click", function (e) {
                e.preventDefault();
                $("#filtro").val("");
                window.location = "consulta.php";
            });
        });
    </script>
</body>
</html>