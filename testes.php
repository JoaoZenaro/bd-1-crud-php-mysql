<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Testes Diversos</title>
</head>
<body>
<?php
    require_once 'conexoes.php';
    // Código para testar as conexões
    conectarPDO();
    conectarMySQLi_PD();
    conectarMySQLi_OO();
?>
</body>
</html>