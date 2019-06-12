<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem Vindo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Olá, <b><?php echo htmlspecialchars($_SESSION["Usuario"]); ?></b>. Seja bem vindo.</h1>
    </div>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset sua senha</a>
        <a href="logout.php" class="btn btn-danger">Sair da sua conta</a>
    </p>
</body>
</html>