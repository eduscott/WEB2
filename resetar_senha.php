<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
require_once "config.php";
 
$nova_senha = $confirmar_senha = "";
$nova_senha_err = $confirmar_senha_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["nova_senha"]))){
        $nova_senha_err = "Por favor insira a nova senha.";     
    } elseif(strlen(trim($_POST["nova_senha"])) < 6){
        $nova_senha_err = "A senha tem que ter no minimo 6 caracteres.";
    } else{
        $nova_senha = trim($_POST["nova_senha"]);
    }

    if(empty(trim($_POST["confirmar_senha"]))){
        $confirmar_senha_err = "Por favor confirme a senha.";
    } else{
        $confirmar_senha = trim($_POST["confirmar_senha"]);
        if(empty($nova_senha_err) && ($nova_senha != $confirmar_senha)){
            $confirmar_senha_err = "As senhas não coincidem.";
        }
    }
        
    if(empty($nova_senha_err) && empty($confirmar_senha_err)){
        $sql = "UPDATE usuarios SET Senha = ? WHERE ID = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("si", $param_Senha, $param_ID);
            
            $param_Senha = password_hash($nova_senha, PASSWORD_DEFAULT);
            $param_ID = $_SESSION["ID"];
            
            if($stmt->execute()){
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Algo deu errado. Tente novamente mais tarde.";
            }
        }
     
        $stmt->close();
    }
    
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resetar Senha</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Resetar Senha</h2>
        <p>Por favor preencha o formulario para resetar sua senha.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>Nova Senha</label>
                <input type="password" name="nova_senha" class="form-control" value="<?php echo $nova_senha; ?>">
                <span class="help-block"><?php echo $nova_senha_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirmar_senha_err)) ? 'has-error' : ''; ?>">
                <label>Confirmar Senha</label>
                <input type="password" name="confirmar_senha" class="form-control">
                <span class="help-block"><?php echo $confirmar_senha_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Enviar">
                <a class="btn btn-link" href="bemvindo.php">Cancelar</a>
            </div>
        </form>
    </div>    
</body>
</html>