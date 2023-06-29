<?php
require_once '../services/conexoes.php';
require_once '../services/utils.php';

function listarDadosMySQLi_PD()
{
    $conn = conectarMySQLi_PD();
    $alunos = mysqli_query($conn, 'SELECT * FROM aluno');
    
    $html = <<<HTML
    <style>#PD th, #PD td {border: 1px solid}</style>
    <table id="PD" style="border-collapse: collapse; border: 2px solid">
    <caption>Relação de Alunos</caption>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Nascimento</th>
        <th>Salário (R$)</th>
    </tr>
    HTML;

    while ($aluno = mysqli_fetch_assoc($alunos)) {
        $html .= <<<HTML
        <tr>
            <td>{$aluno['id_aluno']}</td>
            <td>{$aluno['nome']}</td>
            <td>{$aluno['nascimento']}</td>
            <td>{$aluno['salario']}</td>
        </tr>
        HTML;
    }

    $html .= <<<HTML
    <tfoot><tr><td colspan="4">Data atual: {retornarDataAtual()}</td></tr>
    </table>
    HTML;

    echo $html;
    mysqli_free_result($alunos);
    mysqli_close($conn);
}

function listarDadosMySQLi_OO($filtro = '%%')
{
    $conn = conectarMySQLi_OO();
    $stmt = $conn->prepare('SELECT * FROM aluno WHERE nome LIKE ?');
    $stmt->bind_param('s', $filtro);
    $stmt->execute();
    
    $html = <<<HTML
    <table class="mysqli">
        <caption>Relação de Alunos</caption>
        <tr>
            <th>ID</th>
            <th style="width: 40%;">Nome</th>
            <th>Nascimento</th>
            <th>Salário (R$)</th>
        </tr>
    HTML;

    $alunos = $stmt->get_result();

    while ($aluno = $alunos->fetch_assoc()) {
        $data_nascimento = date('d-m-Y', strtotime($aluno['nascimento']));
        $salario = number_format($aluno['salario'], 2, ',', '.');
        $html .= <<<HTML
        <tr>
            <td>{$aluno['id_aluno']}</td>
            <td>{$aluno['nome']}</td>
            <td style='text-align: center;'>{$data_nascimento}</td>
            <td style='text-align: right;'>{$salario}</td>
        </tr>
        HTML;
    }

    $html .= <<<HTML
    <tfoot><tr><td colspan="4" style="text-align: center">Data atual: {retornarDataAtual()}</td></tr>
    </table>
    HTML;

    echo $html;

    $alunos->free_result();
    $conn->close();
}
