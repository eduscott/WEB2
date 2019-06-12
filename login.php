<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: bemvindo.php");
    exit;
}
 
require_once "config.php";
 
$Usuario = $Senha = "";
$Usuario_err = $Senha_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["Usuario"]))){
        $Usuario_err = "Por favor insira um usuário.";
    } else{
        $Usuario = trim($_POST["Usuario"]);
    }
    
    if(empty(trim($_POST["Senha"]))){
        $Senha_err = "Por favor insira sua senha.";
    } else{
        $Senha = trim($_POST["Senha"]);
    }
    
    if(empty($Usuario_err) && empty($Senha_err)){
        $sql = "SELECT ID, Usuario, Senha FROM usuarios WHERE Usuario = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_usuario);
            
            $param_usuario = $Usuario;
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){                    
                    $stmt->bind_result($ID, $Usuario, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($Senha, $hashed_password)){
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["ID"] = $ID;
                            $_SESSION["Usuario"] = $Usuario;                            
                            
                            header("location: bemvindo.php");
                        } else{
                            $password_err = "Senha incorreta.";
                        }
                    }
                } else{
                   $username_err = "Nenhuma conta encontrada para este nome de usuário.";
                }
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
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Por favor preencha as informações para logar.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($Usuario_err)) ? 'has-error' : ''; ?>">
                <label>Usuário</label>
                <input type="text" name="Usuario" class="form-control" value="<?php echo $Usuario; ?>">
                <span class="help-block"><?php echo $Usuario_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($Senha_err)) ? 'has-error' : ''; ?>">
                <label>Senha</label>
                <input type="password" name="Senha" class="form-control">
                <span class="help-block"><?php echo $Senha_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Entrar">
            </div>
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>.</p>
        </form>
    </div>    
</body>
</html>