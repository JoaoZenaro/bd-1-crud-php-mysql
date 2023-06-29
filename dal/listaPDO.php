<?php
require_once '../services/conexoes.php';
require_once '../services/utils.php';

function listarDadosPDO($filtro = '%%')
{
    $conn = conectarPDO();
    $stmt = $conn->prepare('SELECT * FROM aluno WHERE nome LIKE :nome_aluno');
    $stmt->bindParam(':nome_aluno', $filtro, PDO::PARAM_STR);
    $stmt->execute();
    echo '<div class="container table-responsive">';
    echo '<table class="table table-striped table-bordered table-hover">
 <caption>Relação de Alunos</caption>
 <thead class="table-dark">
 <tr>
 <th>Id</th>
<th>Nome</th>
<th>Nascimento</th>
<th>Salário (R$)</th>
 </tr>
 </thead>';

// echo '<code><pre>';
// var_dump($stmt->fetch());
// echo '<br>';
// print_r($stmt->fetch());
// echo '</pre></code>';

    while ($aluno = $stmt->fetch()) {
        $data_nascimento = date('d-m-Y', strtotime($aluno['nascimento']));
        $salario = number_format($aluno['salario'], 2, ',', '.');
        echo "<tr>
 <td style='width: 10%;'>{$aluno['id_aluno']}</td>
 <td style='width: 40%;'>{$aluno['nome']}</td>
 <td style='width: 25%;' class='text-center'>{$data_nascimento}</td>
 <td style='width: 25%;' class='text-end'>{$salario}</td>
 </tr>";
    }
    echo '<tfoot><tr><td colspan="5" style="text-align: center">Data atual: ' . retornarDataAtual() .
        '</td></tr>';
    echo '</table></div>';


    
    $stmt->execute();
    $alunos_num = $stmt->fetchAll(PDO::FETCH_NUM);
    
    $stmt->execute();
    $alunos_assoc = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt->execute();
    $alunos_obj = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo '<br><h4>Lista de Alunos (<code>PDO::FETCH_NUM</code> / <code>foreach()</code>)</h4>';
    echo '<ul>';
    
    foreach ($alunos_num as $aluno) {
        echo "<li>ID: <strong>{$aluno[0]}</strong> - ";
        echo "Nome: <strong>{$aluno[1]}</strong></li>";
    }
    
    echo '</ul>';
    echo '<br><h4>Lista de Alunos (<code>PDO::FETCH_ASSOC</code> / <code>foreach()</code> associativo)</h4>';
    echo '<ul>';

    foreach ($alunos_assoc as $aluno) {
        echo '<li>';
        foreach ($aluno as $campo => $valor) {
            echo "$campo: <strong>$valor</strong> - ";
        }
        echo '</li>';
    }
    echo '</ul>';
    echo '<br><h4>Lista de Alunos (<code>PDO::FETCH_NUM</code> / <code>for()</code> com matriz)</h4>';
    echo '<ul>';
    
    for ($i = 0; $i < $stmt->rowCount(); $i++) {
        echo "<li>ID: <strong>{$alunos_num[$i][0]}</strong> - ";
        echo "Nome: <strong>{$alunos_num[$i][1]}</strong></li>";
    }

    echo '</ul>';
    echo '<br><h4>Lista de Alunos (<code>PDO::FETCH_OBJ</code> / <code>foreach()</code>)</h4>';
    echo '<ul>';

    foreach ($alunos_obj as $aluno) {
        echo "<li>ID: <strong>{$aluno->id_aluno}</strong> - ";
        echo "Nome: <strong>{$aluno->nome}</strong></li>";
    }
    echo '</ul>';


    // Fecha consulta e conexão, liberando recursos
    $stmt = null;
    $conn = null;
}