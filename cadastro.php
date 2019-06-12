<?php

require_once "config.php";
 
$Usuario = $Senha = $confirmar_senha = "";
$Usuario_err = $Senha_err = $confirmar_senha_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["usuario"]))){
        $Usuario_err = "Insira um usuário.";
    } else{
        $sql = "SELECT id FROM usuarios WHERE Usuario = $Usuario";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_usuario);
            
            $param_usuario = trim($_POST["usuario"]);
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $Usuario_err = "Este nome de usuário já está sendo utilizado.";
                } else{
                    $Usuario = trim($_POST["usuario"]);
                }
            } else{
                echo "Algo deu errado. Tente novamente mais tarde.";
            }
        }
         
        $stmt->close();
    }
    
    if(empty(trim($_POST["Senha"]))){
        $Senha_err = "Insira uma senha.";     
    } elseif(strlen(trim($_POST["Senha"])) < 6){
        $Senha_err = "A senha tem que ter no minimo 6 caracteres.";
    } else{
        $Senha = trim($_POST["Senha"]);
    }
    
    if(empty(trim($_POST["confirmar_senha"]))){
        $confirmar_senha_err = "Por favor confirme a senha.";     
    } else{
        $confirmar_senha = trim($_POST["confirmar_senha"]);
        if(empty($Senha_err) && ($Senha != $confirmar_senha)){
            $confirmar_senha_err = "As senhas não coincidem.";
        }
    }
    if(empty($Usuario_err) && empty($Senha_err) && empty($confirmar_senha_err)){
        
        $sql = "INSERT INTO usuarios (usuario, Senha) VALUES (Usuario, Senha)";
         
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ss", $param_usuario, $param_senha);
            
            $param_usuario = $Usuario;
            $param_senha = password_hash($Senha, PASSWORD_DEFAULT); 

            if($stmt->execute()){
                header("location: login.php");
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
    <title>Cadastrar-se</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Cadastrar-se</h2>
        <p>Por favor preencha o formulario para criar uma conta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($Usuario_err)) ? 'has-error' : ''; ?>">
                <label>Usuário</label>
                <input type="text" name="usuario" class="form-control" value="<?php echo $Usuario; ?>">
                <span class="help-block"><?php echo $Usuario_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($Senha_err)) ? 'has-error' : ''; ?>">
                <label>Senha</label>
                <input type="password" name="Senha" class="form-control" value="<?php echo $Senha; ?>">
                <span class="help-block"><?php echo $Senha_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirmar_senha_err)) ? 'has-error' : ''; ?>">
                <label>Confirmar Senha</label>
                <input type="password" name="confirmar_senha" class="form-control" value="<?php echo $confirmar_senha; ?>">
                <span class="help-block"><?php echo $confirmar_senha_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Enviar">
                <input type="reset" class="btn btn-default" value="Apagar">
            </div>
            <p>Já tem uma conta? <a href="login.php">Clique aqui</a>.</p>
        </form>
    </div>    
</body>
</html>