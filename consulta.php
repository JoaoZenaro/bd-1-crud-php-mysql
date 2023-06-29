<?php
require_once 'services/conexoes.php';
require_once 'services/utils.php';

$conn = conectarPDO();
$nome_pesquisa = $_GET['nome_pesquisa'] ?? ''; // Operador de coalescência nula
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@200;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <title>Listagem com Filtro</title>
</head>
<body>
    <div class="container-fluid" id="listagem_alunos">
        <div class="d-flex justify-content-center mt-2">
            <img src="https://portal.crea-sc.org.br/wp-content/uploads/2019/04/UNOESC-300x100.jpg" width="300px" />
        </div>
        <hr>
        <a href="dal/form_crud.php" class="btn btn-success">
            Incluir um novo aluno
            <i class="fa-solid fa-user"></i>
        </a>
        <hr>
        <form action="consulta.php" method="get">
            <div class="d-flex mt-2 p-3 bg-secondary">
                <div class="input-group col-10 busca">
                    <span class="input-group-text">
                        <i class="fa fa-search"></i>
                    </span>
                    <div class="form-floating">
                        <input id="filtro" type="search" name="nome_pesquisa" class="form-control"
                            value="<?= $nome_pesquisa ?>" placeholder="Entre com o nome do aluno">
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
                    <th>Ações</th>
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
                    <td style="width: 5%;" class="text-center bg-secondary"><?php echo $aluno['id_aluno'] ?></td>
                    <td style="width: 17%;">
                    <a href="detalhes.php?id_aluno=<?php echo $aluno['id_aluno'] ?>">
                        <?= $aluno['nome'] ?>
                    </a>
                    </td>
                    <td style="width: 10%;" class="text-center"><?= $data_nascimento ?></td>
                    <td style="width: 10%;" class="text-end"><?= $salario ?></td>
                    <td style="width: 10%;"><?= $sexo ?></td>
                    <td style="width: 5%;"><?= $ativo ?></td>
                    <td style="width: 18%;"><?= $aluno['nome_curso'] ?></td>
                    <td style="width: 15%;" class="imagem">
                    <a href="detalhes.php?id_aluno=<?= $aluno['id_aluno'] ?>">
                        <?php echo '<img src="data:image/png;base64,' . ($aluno['foto'] ? base64_encode($aluno['foto']) : '') . '" width="200px"/>'; ?>
                        <!-- <?php echo '<img src="data:image/png;base64,' . base64_encode($aluno['foto']) . '" height="200px"/>'; ?> -->
                    </a>
                    </td>
                    <td style="width: 10%;" class="text-center">
                    <span class="icones">
                        <a href="dal/form_crud.php?id_aluno=<?= $aluno['id_aluno'] ?>"><i class="fa-solid fa-edit fa-lg"></i></a>
                        <button type="button" class="btn btn-link p-0 btn-excluir" style="color: red" data-bs-toggle="modal" data-bs-target="#meuModal" data-id="<?= $aluno['id_aluno'] ?>" data-nome="<?= $aluno['nome'] ?>">
                        <span class="fa-solid fa-trash fa-xl"></span>
                        </button>
                    </span>
                    </td>
                </tr>
                <?php
                }
                ?>
                <tfoot>
                <tr>
                    <td colspan="9" style="text-align: center">
                    Data atual: <?= retornarDataAtual() ?>
                    </td>
                </tr>
                </tfoot>
            </table>
            <?php
            $stmt = null;
            $conn = null;
            ?>
            </div>

            <div class="modal fade" id="meuModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Atenção</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button id="ok_confirm" type="button" class="btn btn-primary">Ok</button>
                    </div>
                    </div>
                </div>
            </div>
    </div>

    <script>
        $(document).ready(function() {
            let id_aluno, elemento;

            $("#btnLimpar").on("click", function(e) {
                e.preventDefault();
                $("#filtro").val("");
                window.location = "consulta.php";
            });
            
            $('.btn-excluir').click(function() {
                elemento = $(this).parent().parent().parent();
                id_aluno = $(this).data('id');
                let nome = $(this).data('nome');
                let texto = `Clique em Ok para excluir o registro "<strong>${id_aluno} - ${nome}</strong>"...`;
                $('.modal-body').html(texto);
            });

            $('#ok_confirm').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'dal/excluir_registro.php',
                    data: {
                    id_aluno: id_aluno
                    }
                })
                .done(function(resposta) {
                    const dataResult = JSON.parse(resposta);
                    if (dataResult.statusCode == 200) {
                        console.log('Ok!');
                    } else {
                        console.log('Erro!');
                    }
                });
            $('#meuModal').modal('toggle');
                elemento.css('background', 'tomato');
                elemento.fadeOut(800, function() {
                    $(this).remove();
                    document.location.href = "consulta.php";
                });
            });
        });
    </script>
</body>
</html>